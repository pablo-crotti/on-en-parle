<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatMessage;
use App\Events\NewLike;
use App\Events\NewLikeForRooms;

class LikesController extends Controller
{
    /**
     * Increase the number of likes for a chat message.
     *
     * @param int $messageId The ID of the chat message.
     * @return ChatMessage
     */
    public function like($messageId)
    {
        $message = ChatMessage::find($messageId);
        $message->nb_likes++;
        $message->save();

        event(new NewLike($message->chat_room_id));
        event(new NewLikeForRooms());

        return $message;
    }

    /**
     * Decrease the number of likes for a chat message.
     *
     * @param int $messageId The ID of the chat message.
     * @return ChatMessage
     */
    public function unLike($messageId)
    {
        $message = ChatMessage::find($messageId);
        $message->nb_likes--;
        $message->save();

        event(new NewLike($message->chat_room_id));
        event(new NewLikeForRooms());

        return $message;
    }

    /**
     * Get the total number of likes for a chat room.
     *
     * @param int $chatId The ID of the chat room.
     * @return int
     */
    public function sumChatLikes($chatId)
    {
        $sum = ChatMessage::where('chat_room_id', $chatId)->sum('nb_likes');

        return $sum;
    }
}
