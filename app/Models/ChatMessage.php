<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ChatRoom;
use App\Models\VoiceMessage;
use App\Models\Message;
use App\Models\PhoneCall;

class ChatMessage extends Model
{
    use HasFactory;

    public function room() {
        return $this->belongsTo(ChatRoom::class);
    }
    public function audio() {
        return $this->hasMany(VoiceMessage::class);
    }
    public function text() {
        return $this->hasMany(Message::class);
    }
    public function call() {
        return $this->hasMany(PhoneCall::class);
    }
}
