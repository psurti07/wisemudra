<?php

namespace App\Listeners;

class SendOtpOBBListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        $data = $event->data;
        $res = sendSingleSMS($data['phone'], $data['otp'], $data['panel']);
    }
}
