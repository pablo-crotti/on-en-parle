<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LiveController extends Controller
{
    public function getNearestBroadcast()
    {
        $nearestBroadcast = DB::table('chat_rooms')
            ->whereDate('broadcast_date', '>=', Carbon::today())
            ->orderBy('broadcast_date')
            ->first();

        // $nearestBroadcast contient l'enregistrement le plus proche d'aujourd'hui dans le futur

        return response()->json($nearestBroadcast);
    }

    public function setLive($roomId)
    {
        $chatRoom = DB::table('chat_rooms')
            ->where('id', $roomId)
            ->first();

        if ($chatRoom) {
            $newOnAirStatus = !$chatRoom->on_air;

            DB::table('chat_rooms')
                ->where('id', $roomId)
                ->update(['on_air' => $newOnAirStatus]);

            $updatedChatRoom = DB::table('chat_rooms')
                ->where('id', $roomId)
                ->first();

            return response()->json(['success' => true, 'chatRoom' => $updatedChatRoom]);
        }

        return response()->json(['success' => false]);
    }
}
