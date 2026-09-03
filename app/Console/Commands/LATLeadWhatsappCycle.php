<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\LATLeadWhatsappServices;
use Illuminate\Support\Facades\Log;

class LATLeadWhatsappCycle extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'whatsapp:lat-lead-cycle';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Whatsapp to LAT leads based on remarketing cycle';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            app(LATLeadWhatsappServices::class)->run();
        } catch (\Exception $e) {
            Log::error('Error running LAT Lead Whatsapp command: ' . $e->getMessage());
            $this->error('Command failed: ' . $e->getMessage());
        }
    }
}
