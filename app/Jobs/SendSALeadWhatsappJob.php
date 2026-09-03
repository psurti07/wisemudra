<?php

namespace App\Jobs;

use App\Models\UserRegistration;
use App\Models\LoanApplications;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Throwable;

class SendSALeadWhatsappJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    public UserRegistration $user;
    public LoanApplications $loan;
    
    /**
     * Create a new job instance.
     */
    public function __construct(UserRegistration $user, LoanApplications $loan)
    {
        $this->user = $user;
        $this->loan = $loan;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Log::info("Sending SA Lead Whatsapp to {$this->user->mobile}");
            $eligibilityAmt = calEligiblity($this->loan->monthly_income, $this->loan->currentemi, (($this->loan->loan_type == 2) ? 11.5 : 12.5), $this->loan->loan_amount);
            
            /* Aisensy track code starts here */
            $data1 = array(
				"apiKey" => "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6IjY4MjQ3YTgzNWJmOTcyMzE5ODQ1NTUxYSIsIm5hbWUiOiJLcmVkYmF6IFNlcnZpY2UgSW5kaWEgUHZ0LiBMdGQuIDgxNzAiLCJhcHBOYW1lIjoiQWlTZW5zeSIsImNsaWVudElkIjoiNjgyNDdhODM1YmY5NzIzMTk4NDU1NTE1IiwiYWN0aXZlUGxhbiI6IkZSRUVfRk9SRVZFUiIsImlhdCI6MTc0NzIyMTEyM30.en4SQpQ-K45Hj-BfdcWL7IrJTcOu33DzO0yTuAVSFn4",
				"campaignName" => "self_26may_auto",
				"destination" => "+91".$this->user->mobile,
				"media" => array(
					"url" => "https://whatsapp-media-library.s3.ap-south-1.amazonaws.com/IMAGE/68247a835bf972319845551a/1096124_kbself26may.jpg",
					"filename" => "kb_self_26may.jpg"
				),
				"userName" => $this->user->first_name.' '.$this->user->last_name,
				"tags" => array("Self_RM"),
				"attributes" => array(
					"EligibleAmount" => strval($eligibilityAmt)
				),
				"templateParams" => array('$Name', '$EligibleAmount'),
			);
			$restrack1 = aisensy_track($data1);
			//Log::info($restrack1);
			//return $restrack1;
			/* Aisensy track code ends here */
            // Log::info("SA Lead Whatsapp message sent successfully to {$this->user->mobile}");
        } catch (Throwable $e) {
            Log::error("Failed to send SA Lead Whatsapp to {$this->user->mobile}: " . $e->getMessage());

            // Let Laravel retry if configured
            throw $e;
        }
    }
}
