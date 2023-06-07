<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\ChatMessage;
use App\Models\VoiceMessage;
use App\Events\NewChatMessage;

class AudioController extends Controller
{
    public function store(Request $request, $roomId)
    {
        if ($request->hasFile('audio')) {
            $file = $request->file('audio');
            $fileName = time() . '_' . Str::random(8) . '.webm';
            $path = $file->storeAs('public/rec', $fileName);

            $newChatMessage = new ChatMessage();
            $newChatMessage->content =  $request->message;
            $newChatMessage->chat_room_id = $roomId;
            $newChatMessage->save();

            event(new NewChatMessage($newChatMessage));

            $newMessage = new VoiceMessage();
            $newMessage->chat_message_id = $newChatMessage->id;
            $newMessage->audio_file = $fileName;
            $newMessage->save();

            return $newMessage;

            return response()->json(['path' => $path]);
        }



        return response()->json(['error' => 'No audio file received'], 400);
    }
}
