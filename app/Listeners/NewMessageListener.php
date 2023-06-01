<?php

namespace App\Listeners;

use App\Events\NewChatMessage;

class NewMessageListener
{
    public function __construct()
    {
        //
    }
    /**
     * Handle the event.
     *
     * @param  NewChatMessage  $event
     * @return void
     */
    public function handle(NewChatMessage $event)
    {
        $message = $event->chatMessage;
        // Mettez à jour les messages existants ou effectuez d'autres opérations ici
    }
}
