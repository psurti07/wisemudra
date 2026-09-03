<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WebinarWhatsappCycle extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'whatsapp:webinar-lead-cycle';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Whatsapp to Webinar leads based on remarketing cycle';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $configs = DB::table('interakt_settings')->where('product', 'WEBINAR')->where('type', 'remarketing')->first();
            $now = now();
            $nowFormatted = $now->format('H:i');

            $schedules = config('remarketing.webinarLeadWhatsapp');

            foreach ($schedules as $daysAgo => $times) {
                $response = $wpresponse = "";
                $arrnumbers = 1;
                foreach ($times as $time) {
                    $scheduledTime = Carbon::createFromFormat('H:i', $time);

                    if ($now->diffInMinutes($scheduledTime) == 0) {
                        $targetDate = $now->copy()->subDays($daysAgo)->toDateString();

                         $users = DB::table('user_webinar_registration as c')
                                ->select(
                                    'c.id',
                                    'c.first_name',
                                    'c.last_name',
                                    'c.rec_date',
                                    'c.email',
                                    'c.mobile',
                                    'c.pincode',
                                    'wc.webinar_id',
                                    'wc.userid',
                                )
                                ->join('webinar_order as wc', 'wc.userid', '=', 'c.id')
                                ->whereDate('wc.rec_date', $targetDate)
                                ->where('wc.isUser', 1)
                                ->where('c.isActive', 1)
                                ->where('c.isDelete', 0)
                                ->where('wc.isDelete', 0)
                                ->get();

                        $adminUsers = ['9408881214'];
                        if ($users->isNotEmpty()) {
                            $data1 = array(
                                'rec_date' => date('Y-m-d H:i:s'),
                                'crontype' => 'Webinar Lead Whatsapp',
                                'parentid' => 99, // Hire Agent
                                'cronname' => 'Whatsapp Day - ' . $daysAgo,
                                'msgcount' => $arrnumbers,
                                'msgresponse' => $wpresponse
                            );
                            $insertId = DB::table('sms_log')->insertGetId($data1);

                            foreach ($adminUsers as $admin) {

                                $processPath = 'webinar/user-registration';

                                $buttonValues = new \stdClass();
                                $buttonValues->{"0"} = [$processPath];

                                $data1 = array(
                                    "fullPhoneNumber" => '+91' . $admin,
                                    "callbackData" => "some text here",
                                    "type" => "Template",
                                    "template" => array(
                                        "name" => $configs->template_name,
                                        "languageCode" => "en",
                                        "headerValues" => array(
                                            $configs->img_url
                                        ),
                                        "bodyValues" => array(
                                            $processPath,
                                        ),
                                        "buttonValues" => $buttonValues
                                    )
                                );
                                $response = interakt_message('webinar', $data1, $configs->api_key);
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

                                $processUrl  = route('webinar.process', ['id' => encryptData($user->id)]);
                                $processPath = ltrim(parse_url($processUrl, PHP_URL_PATH), '/') . '?' . parse_url($processUrl, PHP_URL_QUERY);

                                $buttonValues = new \stdClass();
                                $buttonValues->{"0"} = [$processPath];

                                $data2 = array(
                                    "fullPhoneNumber" => '+91' . $user->mobile,
                                    "callbackData" => "some text here",
                                    "type" => "Template",
                                    "template" => array(
                                        "name" => $configs->template_name,
                                        "languageCode" => "en",
                                        "headerValues" => array(
                                            $configs->img_url
                                        ),
                                        "bodyValues" => array(
                                            $processPath,
                                        ),
                                        "buttonValues" => $buttonValues
                                    )
                                );
                                $response = interakt_message('webinar', $data2, $configs->api_key);
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
                        break;
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('Error running Webinar Lead Whatsapp command: ' . $e->getMessage());
            $this->error('Command failed: ' . $e->getMessage());
        }
    }
}
