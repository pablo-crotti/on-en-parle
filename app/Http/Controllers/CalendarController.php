<?php

namespace App\Http\Controllers;

use App\Models\ChatRoom;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    /**
     * Check if a transmission exists on a specific date.
     *
     * @param  int  $year
     * @param  int  $month
     * @param  int  $day
     * @return \App\Models\ChatRoom|bool
     */
    public function hasTransmission($year, $month, $day)
    {
        $date = $year . '-' . $month . '-' . $day;

        $room = ChatRoom::whereRaw('DATE(broadcast_date) = ?', [$date])->first();

        if ($room) {
            return $room;
        } else {
            return false;
        }
    }

    /**
     * Get all broadcast dates from the chat rooms.
     *
     * @return array
     */
    public function getBroadcastDates()
    {
        $rooms = ChatRoom::pluck('broadcast_date', 'id')->all();
        return $rooms;
    }
}
