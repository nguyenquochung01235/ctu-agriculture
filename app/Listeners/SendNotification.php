<?php

namespace App\Listeners;

use App\Events\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\Notification  $event
     * @return void
     */
    public function handle(Notification $event)
    {
        //
    }
}
