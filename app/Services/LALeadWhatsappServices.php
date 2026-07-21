<?php

namespace App\Services;

use App\Jobs\SendLALeadWhatsappJob;
use App\Models\UserRegistration;
use App\Models\LoanApplications;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class LALeadWhatsappServices
{
    public function run()
    {
        try {
            $configs = DB::table('interakt_settings')->where('product','LA')->where('type','remarketing')->first();
            $now = now();
            $nowFormatted = $now->format('H:i');

            $schedules = config('remarketing.laLeadWhatsapp');
            
            foreach ($schedules as $daysAgo => $times) {
                $response = $wpresponse = "";
                $arrnumbers = 1;
                foreach ($times as $time) {
                    $scheduledTime = Carbon::createFromFormat('H:i', $time);
            
                    if ($now->diffInMinutes($scheduledTime) == 0) {
                        $targetDate = $now->copy()->subDays($daysAgo)->toDateString();
            
                        $users = DB::table('user_registrations as r')
                            ->join('loan_applications as a', 'a.userid', '=', 'r.id')
                            ->select(
                                'r.id',
                                'r.update_date',
                                'r.first_name',
                                'r.last_name',
                                'r.mobile',
                                'r.email',
                                'a.monthly_income',
                                'a.loan_type',
                                'a.currentemi',
                                'a.loan_amount'
                            )
                            ->whereDate('r.update_date', '=', $targetDate)
                            ->where('r.update_date', '>=', '2025-06-09 00:00:00')
                            ->where('r.isUser', 1)
                            ->where('r.acc_type', 2)
                            ->where('r.isDnd', 0)
                            ->where('r.isActive', 1)
                            ->where('r.isDelete', 0)
                            ->where('a.isDelete', 0)
                            ->orderBy('r.id', 'asc');
                        // Log::info('SQL Query: ' . $users->toSql());
                        //Log::info('Bindings: ', $users->getBindings());
                        $users = $users->get();
                        $adminUsers = ['7016318366','9408881214','9998807547'];
                        
                        if($users->isNotEmpty()){
                            Log::info('user found in interakt');
                            $data1 = array(
                				'rec_date' => date('Y-m-d H:i:s'),
                				'crontype' => 'Hire Agent Lead Whatsapp',
                				'parentid' => 12, // Hire Agent
                				'cronname' => 'Whatsapp Day - ' . $daysAgo,
                				'msgcount' => $arrnumbers,
                				'msgresponse' => $wpresponse
                			);
                			$insertId = DB::table('sms_log')->insertGetId($data1);
                			
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
                        		$response = interakt_message('hire', $data1, $configs->api_key);
                    			$wpresponse .= $admin . "-" . $response . "|";
                    			/* interact code neds here */
                    			
                    			$data4 = array(
            					    'msgcount' => $arrnumbers,
                					'msgresponse' => $wpresponse
            				    );
				   
				                $query = DB::table('sms_log')->where('id', $insertId)->update($data4);
                                $arrnumbers++;
                            }
                            
                            foreach ($users as $user) {
                                $eligibilityAmt = calEligiblity($user->monthly_income, $user->currentemi, (($user->loan_type == 2) ? 11.5 : 12.5), $user->loan_amount);
                                
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
                        						$user->first_name.' '.$user->last_name, $eligibilityAmt
                        					),
                        				)
                        		);
                    			$response = interakt_message('hire', $data1, $configs->api_key);
                    			$wpresponse .= $user->mobile . "-" . $response . "|";
                    			/* interact code neds here */
                                
                                $data4 = array(
            					    'msgcount' => $arrnumbers,
                				 	'msgresponse' => $wpresponse
        				        );
				   
				                $query = DB::table('sms_log')->where('id', $insertId)->update($data4);
                                $arrnumbers++;
                            }
                        }
                        // Break inner loop after matching one time
                        break;
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('Error in Loan Agent Lead Whatsapp Service: ' . $e->getMessage());
        }
    }
}
