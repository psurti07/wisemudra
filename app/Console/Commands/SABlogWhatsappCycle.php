<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\BlogWhatsappServices;
use Illuminate\Support\Facades\Log;

class SABlogWhatsappCycle extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'whatsapp:blog-remarketing-cycle';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Whatsapp to SA blog remarketing cycle';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            app(BlogWhatsappServices::class)->run();
        } catch (\Exception $e) {
            Log::error('Error running SA Blog Whatsapp command: ' . $e->getMessage());
            $this->error('Command failed: ' . $e->getMessage());
        }
    }
}
