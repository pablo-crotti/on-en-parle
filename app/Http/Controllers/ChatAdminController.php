<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatMessage;
use Inertia\Inertia;


class ChatAdminController extends Controller
{
    public function showChatRoom($id)
    {
        $messages = ChatMessage::where('chat_room_id', $id)->get();

   return Inertia::render('Admin/Reception/Inbox/ContainerRaey', [
            'initialMessages' => $messages
        ]);    /* 
       //retourner à Affichecontenu.blade.php qui se trouve dans ./views les messages en tant que contenu
       //return view('Affichecontenu', ['contenu' => $messages]); */
        
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
