<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatMessage;

class LikesController extends Controller
{
    public function like($messageId) {
        $message = ChatMessage::find($messageId);
        $message->nb_likes++;
        $message->save();

        return $message;
    }

    public function unLike($messageId) {
        $message = ChatMessage::find($messageId);
        $message->nb_likes--;
        $message->save();

        return $message;
    }

    public function sumChatLikes($chatId) {
        $sum = ChatMessage::where('chat_room_id', $chatId)->sum('nb_likes');
        
        return $sum;
    }
}
