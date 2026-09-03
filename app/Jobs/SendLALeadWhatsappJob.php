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

class SendLALeadWhatsappJob implements ShouldQueue
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
            // Log::info("Sending LA Lead Whatsapp to {$this->user->mobile}");
            $eligibilityAmt = calEligiblity($this->loan->monthly_income, $this->loan->currentemi, (($this->loan->loan_type == 2) ? 11.5 : 12.5), loanamount: $this->loan->loan_amount);
            
            /* Aisensy track code starts here */
            $data1 = array(
				"apiKey" => "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6IjY4MjMzYmM3NDdhNDgxN2I0NTY3OGZiMSIsIm5hbWUiOiJLcmVkYmF6IFNlcnZpY2UgSW5kaWEgUHZ0LiBMdGQuIDMwOTciLCJhcHBOYW1lIjoiQWlTZW5zeSIsImNsaWVudElkIjoiNjgyMzNiYzc0N2E0ODE3YjQ1Njc4ZmE4IiwiYWN0aXZlUGxhbiI6IkZSRUVfRk9SRVZFUiIsImlhdCI6MTc0NzEzOTUyN30.1Wa_CJmhtTnwMbx0ETCYzH_Wf7XW1yfOpra7vffWcGg",
				"campaignName" => "hire_26may_auto",
				"destination" => "+91".$this->user->mobile,
				"media" => array(
					"url" => "https://whatsapp-media-library.s3.ap-south-1.amazonaws.com/IMAGE/68233bc747a4817b45678fb1/3121572_kbhire26may.jpg",
					"filename" => "kb_hire_26may.jpg"
				),
				"userName" => $this->user->first_name.' '.$this->user->last_name,
				"tags" => array("Hire_RM"),
				"attributes" => array(
					"EligibleAmount" => strval($eligibilityAmt)
				),
				"templateParams" => array('$Name', '$EligibleAmount'),
			);
			$restrack1 = aisensy_track($data1);
			//Log::info($restrack1);
			//return $restrack1;
			/* Aisensy track code ends here */
            // Log::info("LA Lead Whatsapp message sent successfully to {$this->user->mobile}");
        } catch (Throwable $e) {
            Log::error("Failed to send LA Lead Whatsapp to {$this->user->mobile}: " . $e->getMessage());

            // Let Laravel retry if configured
            throw $e;
        }
    }
}
