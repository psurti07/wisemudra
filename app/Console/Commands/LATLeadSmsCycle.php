<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\LATLeadSmsServices;
use Illuminate\Support\Facades\Log;

class LATLeadSmsCycle extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sms:lat-lead-cycle';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send SMS to LAT leads based on remarketing cycle';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            app(LATLeadSmsServices::class)->run();
        } catch (\Exception $e) {
            Log::error('Error running LAT Lead SMS command: ' . $e->getMessage());
            $this->error('Command failed: ' . $e->getMessage());
        }
    }
}
