<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatMessage;
use Inertia\Inertia;
use App\Models\VoiceMessage;

class ChatAdminController extends Controller
{
    /**
     * Show the chat room with audio messages.
     *
     * @param  int  $id
     * @return \Inertia\Response
     */
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

        $messages = ChatMessage::where('chat_room_id', $id)->with(['text', 'audio', 'call'])->get();

        return Inertia::render('Admin/Reception/Inbox/container', [
            'initialMessages' => $messages,
            'audioChatroom' => $messagesWithAudio,
            'idroom' => $id
        ]);
    }

    /**
     * Show the management chat room.
     *
     * @param  int  $id
     * @return \Inertia\Response
     */
    public function showManagementChatRoom($id)
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

        $messages = ChatMessage::where('chat_room_id', $id)->with(['text', 'audio', 'call'])->get();

        return Inertia::render('Admin/Administration/Management/container', [
            'initialMessages' => $messages,
            'audioChatroom' => $messagesWithAudio,
            'callChatroom' => $messagesWithAudio,
            'idroom' => $id
        ]);
    }

    /**
     * Show the control chat room.
     *
     * @param  int  $id
     * @return \Inertia\Response
     */
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
                    'audio_files' => $audioFiles,
                    'calls' => $calls,
                ];
            });

        $messages = ChatMessage::where('chat_room_id', $id)->with(['text', 'audio', 'call'])->get();

        return Inertia::render('Admin/Administration/Control/container', [
            'initialMessages' => $messages,
            'audioChatroom' => $messagesWithAudio,
            'callChatroom' => $messagesWithAudio,
            'idroom' => $id
        ]);
    }

    /**
     * Show the animator chat room.
     *
     * @param  int  $id
     * @return \Inertia\Response
     */
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
                    'audio_files' => $audioFiles,
                    'calls' => $calls,
                ];
            });

        $messages = ChatMessage::where('chat_room_id', $id)->with(['text', 'audio', 'call'])->get();

        return Inertia::render('Admin/Administration/Animator/container', [
            'initialMessages' => $messages,
            'audioChatroom' => $messagesWithAudio,
            'callChatroom' => $messagesWithAudio,
            'idroom' => $id
        ]);
    }

    /**
     * Update the status of a message.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateMessageStatus(Request $request, $id)
    {
        $message = ChatMessage::findOrFail($id);
        $message->status = $request->input('status');
        $message->save();

        return response()->json(['message' => 'Message updated successfully']);
    }

    /**
     * Update the content of a message.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateMessageContent(Request $request, $id)
    {
        $message = ChatMessage::findOrFail($id);
        $message->content = $request->input('content');
        $message->save();

        return response()->json(['message' => 'Message content updated successfully']);
    }
    /**
     * Delete a message (status 10).
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteMessage($id)
    {
        $message = ChatMessage::findOrFail($id);
        $message->status = 10; 
        $message->save();

        return response()->json(['message' => 'Message deleted successfully']);
    }

    /**
     * Get elements based on status and emission.
     *
     * @param  int  $emission
     * @param  int  $status
     * @return array
     */
    public function getElementsByStatus($emission, $status)
    {
        $elements = [];

        for ($i = 0; $i <= $status; $i++) {
            $elements[$i] = ChatMessage::with(['text', 'audio', 'call'])
                ->where('status', $i)
                ->where('chat_room_id', $emission)
                ->orderBy('created_at', 'DESC')
                ->get();
        }

        return $elements;
    }
}
