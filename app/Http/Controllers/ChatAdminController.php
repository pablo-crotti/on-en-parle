<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatMessage;
use Inertia\Inertia;
use App\Models\VoiceMessage;


class ChatAdminController extends Controller
{
    public function showChatRoom($id)
    {

        $messagesWithAudio = ChatMessage::where('chat_room_id', $id)
        ->has('audio')
        ->with(['text', 'audio', 'call'])
        ->get()
        ->map(function ($message) {
            $audioFiles = $message->audio->map(function ($audio) {
                return $audio->audio_file;
            });
    
            return [
                'id' => $message->id,
                'content' => $message->content,
                'nb_likes' => $message->nb_likes,
                'status' => $message->status,
                'chat_room_id' => $message->chat_room_id,
                'audio_files' => $audioFiles,  // this will be an array of audio files
            ];
        });
    
        
        $messages = ChatMessage::where('chat_room_id', $id) ->with(['text', 'audio', 'call'])->get();
      //  $audio = VoiceMessage::where('chat_room_id', $id)->get();
        error_log("dasdgfds"); // imprime le contenu de $audio dans le log d'erreurs

   return Inertia::render('Admin/Administration/Management/container', [
            'initialMessages' => $messages,
            'audioChatroom' => $messagesWithAudio,
        ]);    /* 
       //retourner à Affichecontenu.blade.php qui se trouve dans ./views les messages en tant que contenu
       //return view('Affichecontenu', ['contenu' => $messages]); */
        
    }

    // Récupéré élément pour partie management
    public function showManagementChatRoom($id)
    {

        $messagesWithAudio = ChatMessage::where('chat_room_id', $id)
        ->has('audio')
        ->with(['text', 'audio', 'call'])
        ->get()
        ->map(function ($message) {
            $audioFiles = $message->audio->map(function ($audio) {
                return $audio->audio_file;
            });
    
            return [
                'id' => $message->id,
                'content' => $message->content,
                'nb_likes' => $message->nb_likes,
                'status' => $message->status,
                'chat_room_id' => $message->chat_room_id,
                'audio_files' => $audioFiles,  // this will be an array of audio files
            ];
        });
    
        
        $messages = ChatMessage::where('chat_room_id', $id) ->with(['text', 'audio', 'call'])->get();
      //  $audio = VoiceMessage::where('chat_room_id', $id)->get();
        error_log("dasdgfds"); // imprime le contenu de $audio dans le log d'erreurs

   return Inertia::render('Admin/Reception/Inbox/ContainerRaey', [
            'initialMessages' => $messages,
            'audioChatroom' => $messagesWithAudio,
        ]);
    }





    public function updateMessageStatus(Request $request, $id)
    {
        $message = ChatMessage::findOrFail($id);
        $message->status = $request->input('status');
        $message->save();

        return response()->json(['message' => 'Message updated successfully']);
    }

    public function updateMessageContent(Request $request, $id)
{
    $message = ChatMessage::findOrFail($id);
    $message->content = $request->input('content');
    $message->save();

    return response()->json(['message' => 'Message content updated successfully']);
}
public function deleteMessage($id)
{
    $message = ChatMessage::findOrFail($id);
    $message->delete();

    return response()->json(['message' => 'Message deleted successfully']);
}

}
