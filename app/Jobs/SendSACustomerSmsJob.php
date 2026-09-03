<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Throwable;


class SendSACustomerSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $dataset;
    protected $schedule;
    protected $arrnumbers;

    /**
     * Create a new job instance.
     */
    public function __construct($dataset, $schedule, $arrnumbers)
    {
        $this->dataset = $dataset;
        $this->schedule = $schedule;
        $this->arrnumbers = $arrnumbers;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $response = sendDynamicXMLSMS($this->dataset);
            $data1 = array(
				'rec_date' => date('Y-m-d H:i:s'),
				'crontype' => 'Self Apply Customer',
				'parentid' => 11, // self apply
				'cronname' => 'SMS Day - ' . $this->schedule,
				'msgcount' => $this->arrnumbers,
				'msgresponse' => $response
			);
			DB::table('sms_log')->insert($data1);
        } catch (Throwable $e) {
            Log::error("Failed to send SA Customer SMS to {$this->user->mobile}: " . $e->getMessage());

            // Let Laravel retry if configured
            throw $e;
        }
    }
}
