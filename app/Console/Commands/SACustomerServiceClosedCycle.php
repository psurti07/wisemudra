<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SACustomerServiceClosed;
use Illuminate\Support\Facades\Log;

class SACustomerServiceClosedCycle extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sms:sa-customer-service-closed-cycle';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Customer Service closed after 7 days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            app(SACustomerServiceClosed::class)->run();
        } catch (\Exception $e) {
            Log::error('Error running SA Customer Service Closed SMS command: ' . $e->getMessage());
            $this->error('Command failed: ' . $e->getMessage());
        }
    }
}
