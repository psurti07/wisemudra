<?php

namespace App\Services;

use App\Jobs\SendSACustomerServiceClosedJob;
use App\Models\UserRegistration;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class SACustomerServiceClosed {
    
    public function run()
    {
        try {
            $now = now();
            $nowFormatted = $now->format('H:i');

            $schedules = config('remarketing.saCustomerServiceClosed');
            
            $msgTemplate = DB::table('sms_list')
                ->where('type', 1)
                ->where('slug', 'sales_cycle_closed')
                ->first()
                ->message ?? '';
    
            $senderId = DB::table('info_pages')
                ->where('slug', 'sa-senderid')
                ->first()
                ->content ?? '';
    
            $dataset = ''; // collect all SMS XMLs
            if($msgTemplate != '#'){
                foreach ($schedules as $daysAgo => $times) {
                    $arrnumbers = 0;
                    foreach ($times as $time) {
                        $scheduledTime = Carbon::createFromFormat('H:i', $time);
    
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
                                    'a.id as appId',
                                    'a.monthly_income',
                                    'a.loan_type',
                                    'a.currentemi',
                                    'a.loan_amount'
                                )
                                ->whereDate('r.update_date', '<=', $targetDate)
                                //->where('r.update_date', '>=', '2025-08-06 00:00:00')
                                ->where('r.isUser', 2)
                                ->where('r.acc_type', 1)
                                ->where('r.isDnd', 0)
                                ->where('r.process_step','!=', 6)
                                ->where('r.isActive', 1)
                                ->where('r.isDelete', 0)
                                ->where('a.isDelete', 0)
                                ->orderBy('r.id', 'asc');
                            $users = $users->get();
                            if ($users->isNotEmpty()) {
                                foreach ($users as $user) {
                                    $dataset .= "<sms>
                                        <user>" . env('SMS_OBB_USERNAME') . "</user>
                                        <password>" . env('SMS_OBB_PASSWORD') . "</password>
                                        <mobiles>{$user->mobile}</mobiles>
                                        <message>{$msgTemplate}</message>
                                        <accusage>1</accusage>
                                        <senderid>{$senderId}</senderid>
                                    </sms>";
                                    /*DB::table('user_registrations')->where('mobile',$user->mobile)->update(['update_date'=>now(), 'process_step'=>6]);
                                    DB::table('application_remarks')->insert([
                                        'rec_date' => now(),
                                        'entry_at' => now(),
                                        'service' => 11,
                                        'subject' => 29,
                                        'notes' => '',
                                        'application_id' => $user->appId,
                                        'staff_id' => 5
                                    ]);*/
                                    $arrnumbers++;   
                                }
                            
                                // Send tracking SMS for job run confirmation
                                $trackingMsg = $msgTemplate;
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
                                    SendSACustomerServiceClosedJob::dispatchSync($dataset, $daysAgo, $arrnumbers);
                                }
                            }
                            break;
                        }
                    }
                }
            } else {
                return;
            }
        } catch (\Exception $e) {
            Log::error('Error in Self Apply Customer SMS Service: ' . $e->getMessage());
        }
    }
    
}
