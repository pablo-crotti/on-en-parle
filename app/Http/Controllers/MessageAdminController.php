<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatMessage;

class MessageAdminController extends Controller
{
    /**
     * Retrieve all chat messages for a specific room.
     *
     * @param Request $request The incoming request.
     * @param int $roomId The ID of the chat room.
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function messages(Request $request, $roomId)
    {
        $messages = ChatMessage::where('chat_room_id', $roomId)
            ->with(['text', 'audio', 'call'])
            ->orderBy('created_at', 'DESC')
            ->get();

        return $messages;
    }
}
