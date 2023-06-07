<?php

namespace App\Events;

use App\Models\AdminMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewAdminMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $adminMessage;
    /**
     * Create a new event instance.
     */
    public function __construct(AdminMessage $adminMessage)
    {
        $this->adminMessage = $adminMessage;
    }
    public function broadcastOn()
    {
        return new Channel('chat.admin.' . $this->adminMessage->chat_room_id);
    }
    
    public function broadcastAs()
    {
        return 'admin.message.new';
    }
}
