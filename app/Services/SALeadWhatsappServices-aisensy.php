<?php

namespace App\Services;

use App\Jobs\SendSALeadWhatsappJob;
use App\Models\UserRegistration;
use App\Models\LoanApplications;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class SALeadWhatsappServices
{
    public function run()
    {
        try {
            $aisensy = DB::table('aisensy_settings')->where('type','remarketing')->where('product','SA')->first();
            
            $now = now();
            $nowFormatted = $now->format('H:i');

            // Log::info('Current Time: ' . $nowFormatted);

            $schedules = config('remarketing.saLeadWhatsapp');
            // Log::info('Loaded Schedules: ' . json_encode($schedules));

            foreach ($schedules as $daysAgo => $times) {
                $response = [];
                $arrnumbers = 0;
                foreach ($times as $time) {
                    $scheduledTime = Carbon::createFromFormat('H:i', $time);

                    // Buffer to allow 1-minute tolerance
                    if ($now->diffInMinutes($scheduledTime) === 0) {
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
                            ->where('r.acc_type', 1)
                            ->where('r.isDnd', 0)
                            ->where('r.isActive', 1)
                            ->where('r.isDelete', 0)
                            ->where('a.isDelete', 0)
                            ->orderBy('r.id', 'asc')
                            ->get();
                        
                        $adminUsers = ['7016318366','9408881214','9998807547'];
                        if($users->isNotEmpty()){
                            foreach ($adminUsers as $admin) {
                                $eligibilityAmt = 500000;
                                    
                                    /* aisensy code starts here */
                                    $data1 = array(
                        				"apiKey" => $aisensy->api_key,
                        				"campaignName" => $aisensy->campaign_name,
                        				"destination" => "+91".$admin,
                        				"media" => array(
                        					"url" => $aisensy->media_url,
                        					"filename" => $aisensy->media_filename
                        				),
                        				"userName" => 'Wisemudra Admin',
                        				"tags" => array("Self_RM"),
                        				"attributes" => array(
                        					"EligibleAmount" => strval($eligibilityAmt)
                        				),
                        				"templateParams" => array('$Name', '$EligibleAmount'),
                        			);
                        			$response[] = aisensy_track($data1);
                        			/* aisensy code neds here */
                                    $arrnumbers++;
                            }

                        
                            foreach ($users as $user) {
                                /*$loan = LoanApplications::where('userid',$user->id)->first();
                                if($loan){*/
                                    $eligibilityAmt = calEligiblity($user->monthly_income, $user->currentemi, (($user->loan_type == 2) ? 11.5 : 12.5), $user->loan_amount);
                                    
                                    /* aisensy code starts here */
                                    $data1 = array(
                        				"apiKey" => $aisensy->api_key,
                        				"campaignName" => $aisensy->campaign_name,
                        				"destination" => "+91".$user->mobile,
                        				"media" => array(
                        					"url" => $aisensy->media_url,
                        					"filename" => $aisensy->media_filename
                        				),
                        				"userName" => $user->first_name.' '.$user->last_name,
                        				"tags" => array("Self_RM"),
                        				"attributes" => array(
                        					"EligibleAmount" => strval($eligibilityAmt)
                        				),
                        				"templateParams" => array('$Name', '$EligibleAmount'),
                        			);
                        			$response[] = aisensy_track($data1);
                        			/* aisensy code neds here */
                                    $arrnumbers++;
                                /*}*/
                            }    
                        }
                        
                        break;
                    }
                }
                if($arrnumbers > 0){
                    $data1 = array(
        				'rec_date' => date('Y-m-d H:i:s'),
        				'crontype' => 'Self Apply Lead',
        				'parentid' => 11, // self
        				'cronname' => 'Whatsapp Day - ' . $daysAgo,
        				'msgcount' => $arrnumbers,
        				'msgresponse' => json_encode($response)
        			);
        			DB::table('sms_log')->insert($data1);
                }
            }
        } catch (\Exception $e) {
            Log::error('Error in Self Apply Lead Whatsapp Service: ' . $e->getMessage());
        }
    }
}
