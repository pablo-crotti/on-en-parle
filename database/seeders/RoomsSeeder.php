<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('chat_rooms')->insert([
            'title' => "",
            'description' => "",
            'image' => "",
            'broadcast_date' => "",
            'audio_file' => "",
            'on_air' => false,
            'closed' => false,
        ]);
        DB::table('chat_rooms')->insert([
            'title' => "",
            'description' => "",
            'image' => "",
            'broadcast_date' => "",
            'audio_file' => "",
            'on_air' => false,
            'closed' => false,
        ]);
        DB::table('chat_rooms')->insert([
            'title' => "",
            'description' => "",
            'image' => "",
            'broadcast_date' => "",
            'audio_file' => "",
            'on_air' => false,
            'closed' => false,
        ]);
        DB::table('chat_rooms')->insert([
            'title' => "",
            'description' => "",
            'image' => "",
            'broadcast_date' => "",
            'audio_file' => "",
            'on_air' => false,
            'closed' => false,
        ]);
    }
}
