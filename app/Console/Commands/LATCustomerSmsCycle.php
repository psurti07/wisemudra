<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\LATCustomerSmsServices;
use Illuminate\Support\Facades\Log;

class LATCustomerSmsCycle extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sms:lat-customer-cycle';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send SMS to Loan Assistant customer based on remarketing cycle';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            app(LATCustomerSmsServices::class)->run();
        } catch (\Exception $e) {
            Log::error('Error running LAT Customer SMS command: ' . $e->getMessage());
            $this->error('Command failed: ' . $e->getMessage());
        }
    }
}
