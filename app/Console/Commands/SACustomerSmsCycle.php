<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SACustomerSmsServices;
use Illuminate\Support\Facades\Log;

class SACustomerSmsCycle extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sms:sa-customer-cycle';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send SMS to SA customer based on remarketing cycle';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            app(SACustomerSmsServices::class)->run();
        } catch (\Exception $e) {
            Log::error('Error running SA Customer SMS command: ' . $e->getMessage());
            $this->error('Command failed: ' . $e->getMessage());
        }
    }
}
