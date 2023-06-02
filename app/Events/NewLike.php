<?php

namespace App\Events;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Models\ChatMessage;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\Channel;

class NewLike implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $roomId;

    public function __construct( $roomId)
    {
        $this->roomId = $roomId;
    }
    public function broadcastOn()
    {
        return new Channel('like.' . $this->roomId);
    }
    
    public function broadcastAs()
    {
        return 'like.new';
    }
}
