<?php

namespace App\Http\Controllers;

use App\Models\PhoneCall;
use Illuminate\Http\Request;
use App\Models\ChatMessage;

class PhoneCallController extends Controller
{
    public function store(Request $request)
{
    $validated = $request->validate([
        'caller'=> 'nullable|min:2|max:16',
        'message' => 'required',
        'chat_room_id' => 'required|exists:chat_rooms,id',
        'status' => 'required|integer'
    ]);

    $chatMessage = new ChatMessage();
    $chatMessage->content = $request->input('message');
    $chatMessage->nb_likes = 0;
    $chatMessage->status = $request->input('status');
    $chatMessage->chat_room_id = $request->input('chat_room_id');
    $chatMessage->save();

    $phoneCall = new PhoneCall();
    $phoneCall->caller = $request->input('caller');
    $phoneCall->chat_message_id = $chatMessage->id; 
    $phoneCall->save();

    return redirect()->back()->with('success', 'Question enregistrée avec succès.');
}




}
