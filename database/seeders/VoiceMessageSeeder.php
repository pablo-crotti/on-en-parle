<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory;

use Faker\Factory as Faker;

  
class VoiceMessageSeeder extends Seeder
{
    public function run()
    {
        $faker = Factory::create();

        $songs = ['song1.mp3', 'song2.mp3', 'song3.mp3', 'song4.mp3', 'song5.mp3'];

        // Get all chat_message ids
        $chatMessageIds = DB::table('chat_messages')->pluck('id')->toArray();

        for ($i = 0; $i < 10; $i++) { // adjust the number to the number of rows you want to insert
            DB::table('voice_messages')->insert([
                'audio_file' => $faker->randomElement($songs), // pick a random song from the array
                'chat_message_id' => $faker->randomElement($chatMessageIds), // pick a random chat_message_id from the array
            ]);
        }
    }
}

