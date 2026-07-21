<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    
    protected $commands = [
        \App\Console\Commands\SALeadWhatsappCycle::class,
        \App\Console\Commands\LALeadWhatsappCycle::class
        /* \App\Console\Commands\SABlogWhatsappCycle::class, */
    ];
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        /*$schedule->command('app:customers-plan-expire')->dailyAt('01:00');*/
        $schedule->command('sms:sa-lead-cycle')->everyFifteenMinutes();
        $schedule->command('sms:la-lead-cycle')->everyFifteenMinutes();
        // $schedule->command('sms:sa-customer-cycle')->everyFifteenMinutes();
       
        $schedule->command('whatsapp:la-lead-cycle')->everyFifteenMinutes();
        $schedule->command('whatsapp:sa-lead-cycle')->everyFifteenMinutes();
        // $schedule->command('whatsapp:blog-remarketing-cycle')->everyFifteenMinutes();
        
        $schedule->command('sms:sa-customer-service-closed-cycle')->everyFifteenMinutes();
        $schedule->command('app:customers-plan-expire')->dailyAt('11:00');

        $schedule->command('whatsapp:webinar-lead-cycle')->everyFifteenMinutes();

        $schedule->command('sms:webinar-lead-cycle')->everyFifteenMinutes();

        $schedule->command('send:webinar-data-hourly')->hourly();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
