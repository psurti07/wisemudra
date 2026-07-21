<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\LALeadWhatsappServices;
use Illuminate\Support\Facades\Log;

class LALeadWhatsappCycle extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'whatsapp:la-lead-cycle';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Whatsapp to LA leads based on remarketing cycle';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            app(LALeadWhatsappServices::class)->run();
        } catch (\Exception $e) {
            Log::error('Error running LA Lead Whatsapp command: ' . $e->getMessage());
            $this->error('Command failed: ' . $e->getMessage());
        }
    }
}
