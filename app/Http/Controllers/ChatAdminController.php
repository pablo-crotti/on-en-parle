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
        ->orderBy('created_at', 'asc') 
        ->get()
        ->map(function ($message) {
            $audioFiles = $message->audio->map(function ($audio) {
                return $audio->audio_file;
            });
            $calls = $message->call->map(function ($call) {
                return [
                    'id' => $call->id,
                    'caller' => $call->caller,
                    'chat_message_id' => $call->chat_message_id,
                ];
            });
    
            return [
                'id' => $message->id,
                'content' => $message->content,
                'nb_likes' => $message->nb_likes,
                'status' => $message->status,
                'chat_room_id' => $message->chat_room_id,
                'audio_files' => $audioFiles, 
                'calls' => $calls,
            ];
        });
    
        
        $messages = ChatMessage::where('chat_room_id', $id) ->with(['text', 'audio', 'call'])->get();
      //  $audio = VoiceMessage::where('chat_room_id', $id)->get();
        error_log("dasdgfds"); // imprime le contenu de $audio dans le log d'erreurs

   return Inertia::render('Admin/Reception/Inbox/container', [
            'initialMessages' => $messages,
            'audioChatroom' => $messagesWithAudio,
            'idroom' => $id
        ]);    /* 
       //  retourner à Affichecontenu.blade.php qui se trouve dans ./views les messages en tant que contenu
       //return view('Affichecontenu', ['contenu' => $messages]); */
        
    }

    // Récupéré élément pour partie management
    public function showManagementChatRoom($id)
    {
        //récupérer les messages avec audio et call dans l'ordre chronologique du plus ancien au plus recents
        $messagesWithAudio = ChatMessage::where('chat_room_id', $id)
        ->has('audio')
        ->with(['text', 'audio', 'call'])
        ->orderBy('created_at', 'asc') 
        ->get()
        ->map(function ($message) {
            $audioFiles = $message->audio->map(function ($audio) {
                return $audio->audio_file;
            });
    
            $calls = $message->call->map(function ($call) {
                return [
                    'id' => $call->id,
                    'caller' => $call->caller,
                    'chat_message_id' => $call->chat_message_id,
                ];
            });
    
            return [
                'id' => $message->id,
                'content' => $message->content,
                'nb_likes' => $message->nb_likes,
                'status' => $message->status,
                'chat_room_id' => $message->chat_room_id,
                'audio_files' => $audioFiles,  // this will be an array of audio files
                'calls' => $calls, // this will be an array of calls
            ];
        });
    
    
        
        $messages = ChatMessage::where('chat_room_id', $id) ->with(['text', 'audio', 'call'])->get();
      //  $audio = VoiceMessage::where('chat_room_id', $id)->get();
        error_log("management"); // imprime le contenu de $audio dans le log d'erreurs

        return Inertia::render('Admin/Administration/Management/container', [
            'initialMessages' => $messages,
            'audioChatroom' => $messagesWithAudio,
            'callChatroom' => $messagesWithAudio,  // the same as audioChatroom because they share the same data structure
            'idroom' => $id
        ]);
    }

    public function showControlChatRoom($id)
    {

        $messagesWithAudio = ChatMessage::where('chat_room_id', $id)
        ->has('audio')
        ->with(['text', 'audio', 'call'])
        ->orderBy('created_at', 'asc') 
        ->get()
        ->map(function ($message) {
            $audioFiles = $message->audio->map(function ($audio) {
                return $audio->audio_file;
            });
    
            $calls = $message->call->map(function ($call) {
                return [
                    'id' => $call->id,
                    'caller' => $call->caller,
                    'chat_message_id' => $call->chat_message_id,
                ];
            });
    
            return [
                'id' => $message->id,
                'content' => $message->content,
                'nb_likes' => $message->nb_likes,
                'status' => $message->status,
                'chat_room_id' => $message->chat_room_id,
                'audio_files' => $audioFiles,  // this will be an array of audio files
                'calls' => $calls, // this will be an array of calls
            ];
        });
    
    
        
        $messages = ChatMessage::where('chat_room_id', $id) ->with(['text', 'audio', 'call'])->get();
      //  $audio = VoiceMessage::where('chat_room_id', $id)->get();
        error_log("Control"); // imprime le contenu de $audio dans le log d'erreurs

        return Inertia::render('Admin/Administration/Control/container', [
            'initialMessages' => $messages,
            'audioChatroom' => $messagesWithAudio,
            'callChatroom' => $messagesWithAudio,  // the same as audioChatroom because they share the same data structure
            'idroom' => $id
        ]);
    }


    public function showAnimatorChatRoom($id)
    {

        $messagesWithAudio = ChatMessage::where('chat_room_id', $id)
        ->has('audio')
        ->with(['text', 'audio', 'call'])
        ->orderBy('created_at', 'asc') 
        ->get()
        ->map(function ($message) {
            $audioFiles = $message->audio->map(function ($audio) {
                return $audio->audio_file;
            });
    
            $calls = $message->call->map(function ($call) {
                return [
                    'id' => $call->id,
                    'caller' => $call->caller,
                    'chat_message_id' => $call->chat_message_id,
                ];
            });
    
            return [
                'id' => $message->id,
                'content' => $message->content,
                'nb_likes' => $message->nb_likes,
                'status' => $message->status,
                'chat_room_id' => $message->chat_room_id,
                'audio_files' => $audioFiles,  // this will be an array of audio files
                'calls' => $calls, // this will be an array of calls
            ];
        });
    
    
        
        $messages = ChatMessage::where('chat_room_id', $id) ->with(['text', 'audio', 'call'])->get();
      //  $audio = VoiceMessage::where('chat_room_id', $id)->get();
        error_log("dasdgfds"); // imprime le contenu de $audio dans le log d'erreurs

        return Inertia::render('Admin/Administration/Animator/container', [
            'initialMessages' => $messages,
            'audioChatroom' => $messagesWithAudio,
            'callChatroom' => $messagesWithAudio,
            'idroom' => $id
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
