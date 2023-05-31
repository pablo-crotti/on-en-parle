<?php

namespace App\Http\Controllers;
use App\Models\ChatRoom;

use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function hasTransmission($year, $month, $day) {

        $date = $year . '-' . $month . '-' . $day;

        $room = ChatRoom::whereRaw('DATE(broadcast_date) = ?', [$date])->first();
        
        if ($room) {
            return $room;
        } else {
            return false;
        }
    }
    public function getBroadcasteDates() {
        $rooms = ChatRoom::pluck('broadcast_date')->all();
        return $rooms;
    }
}
