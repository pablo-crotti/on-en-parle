<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('chat_messages', function (Blueprint $table) {
            //créer un ligne id dans la table chat_messages qui est unique
            $table->id()->unique();
            $table->text('content')->nullable();
            $table->timestamps('timestamp');
            $table->unsignedInteger('nb_likes')->default(0);
            $table->unsignedInteger('status')->default(0);
            $table->foreignId('chat_room_id')->constrained()->onDelete('cascade');            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
    }
};
