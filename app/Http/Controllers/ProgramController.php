<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatRoom;

class ProgramController extends Controller
{
    //nouvelle émission dans la liste
    public function newRoom(Request $request)
    {
        $newChatRoom = new ChatRoom();
        $newChatRoom->title = $request->input('title');
        $newChatRoom->description = $request->input('description');
        $newChatRoom->image = $request->input('banner');
        $newChatRoom->broadcast_date = $request->input('date');
        $newChatRoom->audio_file = $request->input('audio');
        $newChatRoom->save();

        return response()->json($newChatRoom, 201);
    }
}
