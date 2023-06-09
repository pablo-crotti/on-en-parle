<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([

            ChatRoomSeeder::class,
            ChatMessageSeeder::class,
            VoiceMessageSeeder::class,
            ArchiveSeeder::class,
            UserSeeder::class,
        ]);
       
    }
}
