<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ChatMessage;

class ChatRoom extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function messages()
    {
        return $this->hasMany(ChatMessage::class);
    }

    public function messagesAdmin()
    {
        return $this->hasMany(AdminMessage::class);
    }
}
