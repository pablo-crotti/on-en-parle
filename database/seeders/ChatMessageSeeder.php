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
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //fonction qui créer un objet avec des données aléatoireadapter à la base de données chat_messages
        function valeuraleatoirepourBDchatmessage(){
            $faker = \Faker\Factory::create('fr_FR');
            $timestamp = $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null);
            $nb_likes = $faker->numberBetween($min = 0, $max = 100);
            $status = $faker->numberBetween($min = 0, $max = 1);
            $chat_room_id = $faker->numberBetween($min = 1, $max = 3);
            $text = $faker->text($maxNbChars = 200);
            $data = [
                'content' => $text,
                'timestamp' => $timestamp,
                'nb_likes' => $nb_likes,
                'status' => $status,
                'chat_room_id' => $chat_room_id,
            ];
            return $data;
        }
        //fonction qui ajouter un nouveau message avec des données aléatoireadapter à la base de données chat_messages
        function ajoutemsg()
        {
            for ($i=0;$i<10;$i++) {
            $data = valeuraleatoirepourBDchatmessage();
            DB::table('chat_messages')->insert([
                'content' => $data['content'],
                'nb_likes' => $data['nb_likes'],
                'status' => $data['status'],
                'chat_room_id' => $data['chat_room_id'],
            ]);
        }
        }
        ajoutemsg();

    }
}
