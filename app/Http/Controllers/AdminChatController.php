<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\ChatMessage;

class AdminChatController extends Controller
{
    public function getElementsByStatus($emission, $status) {
        $elements = [];

        for ($i = 0; $i <= $status; $i++) {
            $elements[$i] = ChatMessage::with(['text', 'audio', 'call'])
            ->where('status', $i)
            ->where('chat_room_id', $emission)
            ->orderBy('created_at', 'DESC')
            ->get();
        }

        return $elements;
    }
}
