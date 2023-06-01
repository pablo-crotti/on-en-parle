<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IndexRoomController extends Controller
{
    public function indexRoom()
    {
        // Récupérer les données de la BD
        $rooms = DB::table('chat_rooms')->get();

        // Vérifier s'il y a une room en cours de diffusion
        $onAirRoom = $rooms->firstWhere('on_air', 1);

        if ($onAirRoom) {
            return $onAirRoom;
        } else {
            // Trouver la room avec la date de diffusion future la plus proche
            $futureRooms = $rooms->where('broadcast_date', '>', now())->sortBy('broadcast_date');

            if ($futureRooms->isNotEmpty()) {
                return $futureRooms->first();
            }

            // Retourner la room avec la date la plus proche si aucune room en direct ni dans le futur
            $closestRoom = $rooms->sortBy('broadcast_date', true)->first();

            return $closestRoom;
        }
    }
}
