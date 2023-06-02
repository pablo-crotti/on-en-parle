<?php

namespace App\Events;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Models\ChatMessage;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\Channel;

class NewLikeForRooms implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct()
    {
    }
    public function broadcastOn()
    {
        return new Channel('like.rooms');
    }
    
    public function broadcastAs()
    {
        return 'like.rooms.new';
    }
}
