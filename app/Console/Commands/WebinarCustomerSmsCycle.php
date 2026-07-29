<?php

namespace App\Console\Commands;

use App\Jobs\SendWebinarCustomerSmsJob;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WebinarCustomerSmsCycle extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sms:webinar-customer-cycle';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send SMS to Webinar Customer based on remarketing cycle';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $now = now();
            $nowFormatted = $now->format('H:i');

            $schedules = config('remarketing.webinarCustomerSms');

            $msgTemplate = DB::table('sms_list')
                ->where('type', 5)
                ->where('slug', 'customer_sms')
                ->first()
                ->message ?? '';

            $senderId = DB::table('info_pages')
                ->where('slug', 'webinar-senderid')
                ->first()
                ->content ?? '';

            $dataset = ''; // collect all SMS XMLs
            if ($msgTemplate != '#') {
                foreach ($schedules as $daysAgo => $times) {
                    //Log::info('days ago - '. $daysAgo);
                    $arrnumbers = 0;
                    foreach ($times as $time) {
                        //Log::info('times - '. $time);
                        $scheduledTime = Carbon::createFromFormat('H:i', $time);
                        //Log::info('schedule time - '. $scheduledTime);
                        if ($now->diffInMinutes($scheduledTime) === 0) {
                            $targetDate = $now->copy()->subDays($daysAgo)->toDateString();

                            $users = DB::table('user_webinar_registration')
                                ->select(
                                    'id',
                                    'first_name',
                                    'last_name',
                                    'rec_date',
                                    'email',
                                    'mobile',
                                    'pincode',
                                )
                                ->whereDate('rec_date', '=', $targetDate)
                                ->where('isUser', 2)
                                ->where('isActive', 1)
                                ->where('isDelete', 0)
                                ->orderBy('id', 'asc')
                                ->get();

                            if ($users->isNotEmpty()) {
                                foreach ($users as $user) {

                                    $dataset .= "<sms>
                                            <user>" . config('constant.SMS_OBB_USERNAME') . "</user>
                                            <password>" . config('constant.SMS_OBB_PASSWORD') . "</password>
                                            <mobiles>{$user->mobile}</mobiles>
                                            <message>{$msgTemplate}</message>
                                            <accusage>1</accusage>
                                            <senderid>{$senderId}</senderid>
                                        </sms>";
                                    $arrnumbers++;
                                }

                                $dataset .= "<sms>
                                    <user>" . config('constant.SMS_OBB_USERNAME') . "</user>
                                    <password>" . config('constant.SMS_OBB_PASSWORD') . "</password>
                                    <mobiles>9408881214</mobiles>
                                    <message>{$msgTemplate}</message>
                                    <accusage>1</accusage>
                                    <senderid>{$senderId}</senderid>
                                </sms>";
                                // Send SMS only if dataset has value
                                if (!empty($dataset)) {
                                    SendWebinarCustomerSmsJob::dispatchSync($dataset, $daysAgo, $arrnumbers);
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
            Log::error('Error running SA Customer SMS command: ' . $e->getMessage());
            $this->error('Command failed: ' . $e->getMessage());
        }
    }
}
