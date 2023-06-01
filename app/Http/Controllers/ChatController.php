<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatMessage;
use App\Models\ChatRoom;
use Illuminate\Support\Facades\Auth;
use App\Events\NewChatMessage;
use App\Models\Message;

class ChatController extends Controller
{   
    /**
     * Retrieve all chat rooms.
     *
     * @param Request $request The incoming request.
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function rooms(Request $request)
    {
        return ChatRoom::orderBy('on_air', 'DESC')
            ->orderBy('closed', 'ASC')
            ->get();
    }

    public function room(Request $request, $roomId)
    {
        return ChatRoom::where('id', $roomId)->first();
    }

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
            ->with(['text', 'audio'])
            ->whereIn('status', [0, 1, 2, 3, 4, 5])
            ->orderBy('created_at', 'DESC')
            ->get();

        return $messages;
    }
    /**
     * Create a new chat message for a specific room.
     *
     * @param Request $request The incoming request.
     * @param int $roomId The ID of the chat room.
     * @return App\Models\ChatMessage
     */
    public function newMessage(Request $request, $roomId)
    {
        $newChatMessage = new ChatMessage();
        $newChatMessage->content =  $request->message;
        $newChatMessage->chat_room_id = $roomId;
        $newChatMessage->save();

        $newMessage = new Message();
        $newMessage->chat_message_id = $newChatMessage->id;
        $newMessage->save();

        broadcast(new NewChatMessage($newChatMessage))->toOthers();

        return $newMessage; 
       
    }
}
