<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class ChatMessageSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        
        
        for($i = 1; $i <= 20; $i++)
        {
            DB::table('chat_messages')->insert([
                'content' => $faker->text(200),
                'nb_likes' => $faker->numberBetween(0, 100),
                'status' => $faker->boolean(),
                'chat_room_id' => $faker->numberBetween(1, 3),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}