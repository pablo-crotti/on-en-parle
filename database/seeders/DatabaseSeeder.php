<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
<<<<<<< HEAD
            ChatRoomSeeder::class,
        ]);
        $this->call([
            ChatMessageSeeder::class,
=======
          
            VoiceMessageSeeder::class,
>>>>>>> e1b469554c89e19001a58f3b4eabca75e5ad1050
        ]);
    }
}
