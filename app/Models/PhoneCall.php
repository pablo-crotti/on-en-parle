<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhoneCall extends Model
{
    use HasFactory;
    
    public $timestamps = false;

    public function message() {
        return $this->belongsTo(ChatMessage::class);
    }
}
