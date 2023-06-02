<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatMessage;
use App\Events\NewLike;
use App\Events\NewLikeForRooms;

class LikesController extends Controller
{
    public function like($messageId) {
        $message = ChatMessage::find($messageId);
        $message->nb_likes++;
        $message->save();

        event(new NewLike($message->chat_room_id));
        event(new NewLikeForRooms());


        return $message;
    }

    public function unLike($messageId) {
        $message = ChatMessage::find($messageId);
        $message->nb_likes--;
        $message->save();

        event(new NewLike($message->chat_room_id));
        event(new NewLikeForRooms());

        return $message;
    }

    public function sumChatLikes($chatId) {
        $sum = ChatMessage::where('chat_room_id', $chatId)->sum('nb_likes');
        
        return $sum;
    }
}
