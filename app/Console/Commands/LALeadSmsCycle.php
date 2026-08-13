<?php

namespace App\Console\Commands;

use App\Jobs\SendLALeadSmsJob;
use Illuminate\Console\Command;
use App\Services\LALeadSmsServices;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LALeadSmsCycle extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sms:la-lead-cycle';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send SMS to LA leads based on remarketing cycle';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            try {
                $now = now();
                $nowFormatted = $now->format('H:i');

                $schedules = config('remarketing.laLeadSms');

                $msgTemplate = DB::table('sms_list')
                    ->where('type', 2)
                    ->where('slug', 'remarketing_sms')
                    ->first()
                    ->message ?? '';

                $senderId = DB::table('info_pages')
                    ->where('slug', 'la-senderid')
                    ->first()
                    ->content ?? '';

                $dataset = ''; // collect all SMS XMLs
                if ($msgTemplate != '#') {
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
                                        'a.monthly_income',
                                        'a.loan_type',
                                        'a.currentemi',
                                        'a.loan_amount'
                                    )
                                    ->whereDate('r.update_date', '=', $targetDate)
                                    //->where('r.update_date', '>=', '2025-08-06 00:00:00')
                                    ->where('r.isUser', 1)
                                    ->where('r.acc_type', 2)
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
                                        <user>" . config('constant.SMS_OBB_LA_USERNAME') . "</user>
                                        <password>" . config('constant.SMS_OBB_LA_PASSWORD') . "</password>
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
                                    $adminUsers = config('constant.REMARKETING_MOBILE_NUMBERS');
                                    foreach ($adminUsers as $mobile) {
                                        $dataset .= "
                                        <sms>
                                            <user>" . config('constant.SMS_OBB_LA_USERNAME') . "</user>
                                            <password>" . config('constant.SMS_OBB_LA_PASSWORD') . "</password>
                                            <mobiles>{$mobile}</mobiles>
                                            <message>{$trackingMsg}</message>
                                            <accusage>1</accusage>
                                            <senderid>{$senderId}</senderid>
                                        </sms>";
                                    }
                                    // Send SMS only if dataset has value
                                    if (!empty($dataset)) {
                                        SendLALeadSmsJob::dispatchSync($dataset, $daysAgo, $arrnumbers);
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
                Log::error('Error in Loan Agent Lead SMS Service: ' . $e->getMessage());
            }
        } catch (\Exception $e) {
            Log::error('Error running LA Lead SMS command: ' . $e->getMessage());
            $this->error('Command failed: ' . $e->getMessage());
        }
    }
}
