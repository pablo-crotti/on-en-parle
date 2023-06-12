<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Events\NewLiveStatus;

class LiveController extends Controller
{
    /**
     * Get the nearest broadcast room.
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function getNearestBroadcast()
    {
        $nearestBroadcast = DB::table('chat_rooms')
            ->whereDate('broadcast_date', '>=', Carbon::today())
            ->orderBy('broadcast_date')
            ->first();

        return response()->json($nearestBroadcast);
    }

    /**
     * Set the live status for a chat room.
     *
     * @param int $roomId The ID of the chat room.
     * @return Illuminate\Http\JsonResponse
     */
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

            event(new NewLiveStatus());

            return response()->json(['success' => true, 'chatRoom' => $updatedChatRoom]);
        }

        return response()->json(['success' => false]);
    }

    /**
     * Check if there is a chat room currently live.
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function isLive()
    {
        $chatRoom = DB::table('chat_rooms')
            ->where('on_air', true)
            ->first();

        if ($chatRoom) {
            return response()->json(['success' => true, 'chatRoom' => $chatRoom]);
        }

        return response()->json(['success' => false]);
    }
}
