<?php

namespace App\Http\Controllers;

use App\Events\NewAdminMessage;
use App\Models\AdminMessage;
use Illuminate\Http\Request;

class AdminMessagesController extends Controller
{
    /**
     * Get the admin messages for a specific chat room.
     *
     * @param  int  $roomId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAdminMessages($roomId)
    {
        $messages = AdminMessage::with('user:id,name')
            ->where('chat_room_id', $roomId)
            ->orderBy('created_at', 'DESC')
            ->get();

        return $messages;
    }

    /**
     * Create a new admin message for a specific chat room.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $roomId
     * @return \App\Models\AdminMessage
     */
    public function newMessage(Request $request, $roomId)
    {
        $message = AdminMessage::create([
            'chat_room_id' => $roomId,
            'user_id' => $request->userId,
            'content' => $request->message
        ]);

        event(new NewAdminMessage($message));

        return $message;
    }
}
