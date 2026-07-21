<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SALeadSmsServices;
use Illuminate\Support\Facades\Log;

class SALeadSmsCycle extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sms:sa-lead-cycle';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send SMS to SA leads based on remarketing cycle';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            app(SALeadSmsServices::class)->run();
        } catch (\Exception $e) {
            Log::error('Error running SA Lead SMS command: ' . $e->getMessage());
            $this->error('Command failed: ' . $e->getMessage());
        }
    }
}
