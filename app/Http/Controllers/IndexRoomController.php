<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IndexRoomController extends Controller
{
    /**
     * Retrieve the chat room to display on the index page.
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function indexRoom()
    {
        $rooms = DB::table('chat_rooms')->get();

        $onAirRoom = $rooms->firstWhere('on_air', 1);

        if ($onAirRoom) {
            return $onAirRoom;
        } else {
            $futureRooms = $rooms->where('broadcast_date', '>=', now()->toDateString())->sortBy('broadcast_date');

            if ($futureRooms->isNotEmpty()) {
                return $futureRooms->first();
            }

            $closestRoom = $rooms->sortBy('broadcast_date', true)->first();

            return $closestRoom;
        }
    }
}
