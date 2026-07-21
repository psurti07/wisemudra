<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\LALeadSmsServices;
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
            app(LALeadSmsServices::class)->run();
        } catch (\Exception $e) {
            Log::error('Error running LA Lead SMS command: ' . $e->getMessage());
            $this->error('Command failed: ' . $e->getMessage());
        }
    }
}
