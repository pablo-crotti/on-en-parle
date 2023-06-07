<?php

namespace App\Http\Controllers;

use App\Models\AdminMessage;
use Illuminate\Http\Request;

class AdminMessagesController extends Controller
{
    public function getAdminMessages($roomId)
    {
       $messages = AdminMessage::where('chat_room_id', $roomId)
            ->orderBy('created_at', 'DESC')
            ->get();

        return $messages;
    }
}
