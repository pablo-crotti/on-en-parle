<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatRoom;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProgramController extends Controller
{
       public function room(Request $request, $roomId)
        {
            return ChatRoom::where('id', $roomId)->first();
        }

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

    //update une émission
    public function updateRoom(Request $request, $roomId,)
    {
        DB::table('chat_rooms')
                        ->where('id', $roomId)
                        ->update(['title' => $request->input('title'),
                                  'description' => $request->input('description'),
                                  'image' => $request->input('banner'),
                                  'broadcast_date' => $request->input('date'),
                                  'audio_file' => $request->input('audio')
                                ]);
    }

    public function getUpcomingBroadcasts()
    {
        $currentDate = Carbon::now();

        $broadcasts = ChatRoom::where('broadcast_date', '>=', $currentDate)
            ->orderBy('broadcast_date', 'asc')
            ->get();

        return $broadcasts;
    }
}