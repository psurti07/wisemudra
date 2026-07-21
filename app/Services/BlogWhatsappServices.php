<?php

namespace App\Services;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class BlogWhatsappServices
{
    public function run()
    {
        try {
            $configs = DB::table('interakt_settings')->where('product','SA')->where('type','blog')->first();
            
            $now = now();
            $nowFormatted = $now->format('H:i');

            $schedules = config('remarketing.saBlogWhatsapp');
            
            foreach ($schedules as $daysAgo => $times) {
                $response = $wpresponse = "";
                $arrnumbers = 0;
                foreach ($times as $time) {
                    $scheduledTime = Carbon::createFromFormat('H:i', $time);
                    if ($now->diffInMinutes($scheduledTime) == 0) {
                        $targetDate = $now->copy()->subDays($daysAgo)->toDateString();
                        
                        $users = DB::table('bulksms as r')
                            ->whereDate('r.rec_Date', '=', $targetDate)
                            ->where('r.isDnd', 0)
                            ->where('r.isDelete', 0)
                            ->orderBy('r.id', 'asc')
                            ->get();

                        $adminUsers = ['7016318366','9998807547','9408881214'];
                        if($users->isNotEmpty()){
                            /* admin no. foreach */
                            foreach ($adminUsers as $admin) {
                                $eligibilityAmt = 500000;
                                
                                    /* interact code starts here */
                        			$data1 = array(
                            			"fullPhoneNumber" => '+91'.$admin,
                            			"callbackData"=> "some text here",
                            			"type"=> "Template",
                            			"template"=> array(
                            					"name"=> $configs->template_name,
                            					"languageCode"=> "en",
                            					"headerValues"=> array(
                            						$configs->img_url
                            					),
                            					"bodyValues"=> array(
                            						'$name', $eligibilityAmt
                            					),
                            				)
                            		);
                        			$response = interakt_message('self', $data1, $configs->api_key);
                        			$wpresponse .= $admin . "-" . $response . "|";
                        			/* interact code neds here */
                                    $arrnumbers++;        
                            }

                            /* users foreach */
                            foreach ($users as $user) {
                                $eligibilityAmt = '480000';
                                
                                /* interact code starts here */
                    			$data1 = array(
                        			"fullPhoneNumber" => '+91'.$user->mobile,
                        			"callbackData"=> "some text here",
                        			"type"=> "Template",
                        			"template"=> array(
                        					"name"=> $configs->template_name,
                        					"languageCode"=> "en",
                        					"headerValues"=> array(
                        						$configs->img_url
                        					),
                        					"bodyValues"=> array(
                        						$user->fullname ?? 'Blog User', $eligibilityAmt
                        					),
                        				)
                        		);
                    			$response = interakt_message('self', $data1, $configs->api_key);
                    			$wpresponse .= $user->mobile . "-" . $response . "|";
                    			/* interact code neds here */
                    			
                    			$arrnumbers++;
                            }    
                            $data1 = array(
                				'rec_date' => date('Y-m-d H:i:s'),
                				'crontype' => 'Self Apply Blog',
                				'parentid' => 11, // Self Apply
                				'cronname' => 'Whatsapp Day - ' . $daysAgo,
                				'msgcount' => $arrnumbers,
                				'msgresponse' => $wpresponse
                			);
            			    $insertId = DB::table('sms_log')->insertGetId($data1);    
                        }
                        break;
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('Error in Self Apply Blog Lead Whatsapp Service: ' . $e->getMessage());
        }
    }
}
