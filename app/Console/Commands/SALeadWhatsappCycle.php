<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SALeadWhatsappServices;
use Illuminate\Support\Facades\Log;

class SALeadWhatsappCycle extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'whatsapp:sa-lead-cycle';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Whatsapp to SA leads based on remarketing cycle';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            app(SALeadWhatsappServices::class)->run();
        } catch (\Exception $e) {
            Log::error('Error running SA Lead Whatsapp command: ' . $e->getMessage());
            $this->error('Command failed: ' . $e->getMessage());
        }
    }
}
