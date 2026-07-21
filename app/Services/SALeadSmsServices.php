<?php

namespace App\Services;

use App\Jobs\SendSALeadSmsJob;
use App\Models\UserRegistration;
use App\Models\LoanApplications;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class SALeadSmsServices
{
    public function run()
    {
        try {
            $now = now();
            $nowFormatted = $now->format('H:i');

            $schedules = config('remarketing.saLeadSms');

            $msgTemplate = DB::table('sms_list')
                ->where('type', 1)
                ->where('slug', 'remarketing_sms')
                ->first()
                ->message ?? '';

            $senderId = DB::table('info_pages')
                ->where('slug', 'sa-senderid')
                ->first()
                ->content ?? '';

            $dataset = ''; // collect all SMS XMLs
            if($msgTemplate != '#'){
                foreach ($schedules as $daysAgo => $times) {
                    //Log::info('days ago - '. $daysAgo);
                    $arrnumbers = 0;
                    foreach ($times as $time) {
                        //Log::info('times - '. $time);
                        $scheduledTime = Carbon::createFromFormat('H:i', $time);
                        //Log::info('schedule time - '. $scheduledTime);
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
                                //->where('r.update_date', '>=', '2025-08-06 00:00:00')
                                ->where('r.isUser', 1)
                                ->where('r.acc_type', 1)
                                ->where('r.isDnd', 0)
                                ->where('r.isActive', 1)
                                ->where('r.isDelete', 0)
                                ->where('a.isDelete', 0)
                                ->orderBy('r.id', 'asc')
                                ->get();
                            
                            if ($users->isNotEmpty()) {
                                foreach ($users as $user) {
                                    /*$loan = LoanApplications::where('userid', $user->id)->first();
    
                                    if ($loan) {*/
                                        $eligibilityAmt = calEligiblity(
                                            $user->monthly_income,
                                            $user->currentemi,
                                            ($user->loan_type == 2) ? 11.5 : 12.5,
                                            $user->loan_amount
                                        );
    
                                        $personalizedMsg = str_ireplace('{#varamount#}', $eligibilityAmt, $msgTemplate);
    
                                        $dataset .= "<sms>
                                            <user>" . env('SMS_OBB_USERNAME') . "</user>
                                            <password>" . env('SMS_OBB_PASSWORD') . "</password>
                                            <mobiles>{$user->mobile}</mobiles>
                                            <message>{$personalizedMsg}</message>
                                            <accusage>1</accusage>
                                            <senderid>{$senderId}</senderid>
                                        </sms>";
                                    /*}*/
                                    $arrnumbers++;
                                }
    
    
                                // Send tracking SMS for job run confirmation
                                $trackingMsg = str_ireplace('{#varamount#}', '500000', $msgTemplate);
                                $dataset .= "<sms>
                                    <user>" . env('SMS_OBB_USERNAME') . "</user>
                                    <password>" . env('SMS_OBB_PASSWORD') . "</password>
                                    <mobiles>7016318366</mobiles>
                                    <message>{$trackingMsg}</message>
                                    <accusage>1</accusage>
                                    <senderid>{$senderId}</senderid>
                                </sms><sms>
                                    <user>" . env('SMS_OBB_USERNAME') . "</user>
                                    <password>" . env('SMS_OBB_PASSWORD') . "</password>
                                    <mobiles>9998807547</mobiles>
                                    <message>{$trackingMsg}</message>
                                    <accusage>1</accusage>
                                    <senderid>{$senderId}</senderid>
                                </sms><sms>
                                    <user>" . env('SMS_OBB_USERNAME') . "</user>
                                    <password>" . env('SMS_OBB_PASSWORD') . "</password>
                                    <mobiles>9408881214</mobiles>
                                    <message>{$trackingMsg}</message>
                                    <accusage>1</accusage>
                                    <senderid>{$senderId}</senderid>
                                </sms>";
                                // Send SMS only if dataset has value
                                if (!empty($dataset)) {
                                    SendSALeadSmsJob::dispatchSync($dataset, $daysAgo, $arrnumbers);
                                }
                            }
                            break; // match found, break the inner loop
                        }
                    }
                }    
            } else {
                return;    
            }
        } catch (\Exception $e) {
            Log::error('Error in Self Apply Lead SMS Service: ' . $e->getMessage());
        }
    }

}
