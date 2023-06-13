<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatRoom;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Events\NewRoom;

class ProgramController extends Controller
{
    /**
     * Retrieve a specific chat room.
     *
     * @param Request $request The incoming request.
     * @param int $roomId The ID of the chat room.
     * @return ChatRoom|null
     */
    public function room(Request $request, $roomId)
    {
        return ChatRoom::where('id', $roomId)->first();
    }

    /**
     * Create a new chat room.
     *
     * @param Request $request The incoming request.
     * @return Illuminate\Http\JsonResponse
     */
    public function newRoom(Request $request)
    {
        $newChatRoom = new ChatRoom();
        $newChatRoom->title = $request->input('title');
        $newChatRoom->description = $request->input('description');
        $newChatRoom->image = $request->input('banner');
        $newChatRoom->broadcast_date = $request->input('date');
        $newChatRoom->audio_file = $request->input('audio');
        $newChatRoom->save();

        event(new NewRoom());

        return response()->json($newChatRoom, 201);
    }

    /**
     * Update a chat room.
     *
     * @param Request $request The incoming request.
     * @param int $roomId The ID of the chat room.
     * @return void
     */
    public function updateRoom(Request $request, $roomId)
    {
        DB::table('chat_rooms')
            ->where('id', $roomId)
            ->update([
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'image' => $request->input('banner'),
                'broadcast_date' => $request->input('date'),
                'audio_file' => $request->input('audio')
            ]);
        event(new NewRoom());
    }

    /**
     * Get the upcoming broadcast chat rooms.
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function getUpcomingBroadcasts()
    {
        $currentDate = Carbon::now();

        $broadcasts = ChatRoom::whereDate('broadcast_date', '>=', $currentDate)
            ->orderBy('broadcast_date', 'asc')
            ->get();

        return $broadcasts;
    }

    /**
     * Delete a chat room.
     *
     * @param int $id The ID of the chat room.
     * @return Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $room = ChatRoom::find($id);

        if ($room) {
            $room->delete();

            return response()->json(['message' => 'Chat room deleted successfully']);
        }

        event(new NewRoom());
        
        return response()->json(['message' => 'Chat room not found'], 404);
    }
}
