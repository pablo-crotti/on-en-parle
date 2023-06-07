<?php

namespace App\Http\Controllers;

use App\Events\NewAdminMessage;
use App\Models\AdminMessage;
use Illuminate\Http\Request;

class AdminMessagesController extends Controller
{
    public function getAdminMessages($roomId)
    {
       $messages = AdminMessage::with('user:id,name')
            ->where('chat_room_id', $roomId)
            ->orderBy('created_at', 'DESC')
            ->get();

        return $messages;
    }

    public function newMessage(Request $request, $roomId)
    {
        // dd($request->userIdmId);
        $message = AdminMessage::create([
            'chat_room_id' => $roomId,
            'user_id' => $request->userId,
            'content' => $request->message
        ]);

        event(new NewAdminMessage($message));

        return $message;
    }
}
