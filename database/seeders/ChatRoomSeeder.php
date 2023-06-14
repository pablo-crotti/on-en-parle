<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChatRoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('chat_rooms')->insert([
            'title' => 'Guichet spécial: programmes radio',
            'description' => "Qu'attendez-vous d'une radio de service publique? Etes-vous satisfaits de nos programmes? Estimez-vous en avoir pour votre argent? Sinon, qu'aimeriez-vous entendre ou voir changer?
                Laissez-nous messages par téléphone en direct au 0800 108 208, par message sur Whatsapp, Telegram, Signal et Threema au 076 206 12 12, ou encore par email à l'adresse courriel onenparle@rts.ch. Les responsables des programmes vous répondent jeudi 25 mai, dès 9h07.",
            'image' => 'https://www.rts.ch/2023/05/24/09/51/14041188.image?&w=1630&h=750',
            'broadcast_date' => '2023-05-26 09:07:00',
            'audio_file' => 'https://rts-aod-dd.akamaized.net/ww/14015910/5e94037e-11ee-3854-ac87-fdf8f52c2cc7.mp3',     
        ]);

        DB::table('chat_rooms')->insert([
            'title' => 'Raconter des histoires aux enfants: amusant et… utile!',
            'description' => "La Journée suisse de la lecture à voix haute se déroule aujourd'hui dans tout le pays.
                Bernard Utz, écrivain et coordinateur de l'événement pour la Suisse romande, explique à Mathieu Truffer pourquoi il est important de lire des histoires à voix haute aux jeunes enfants. Il donne aussi conseils pratiques et astuces pour le faire le mieux possible.",
            'image' => 'https://www.rts.ch/2023/05/22/09/42/14041175.image?&w=1630&h=750',
            'broadcast_date' => '2023-05-27',
            'audio_file' => 'https://rts-aod-dd.akamaized.net/ww/14012072/d3ac76a5-9e44-3d55-b1ea-173254c8ad60.mp3',     
        ]);

        DB::table('chat_rooms')->insert([
            'title' => 'Guichet: le rhume des foins',
            'description' => "En Suisse, 20% de la population souffrent d'une allergie pollinique. Les symptômes débutent avec la floraison des arbres en début d'année mais la grande majorité des gens réagissent aux graminées, dès avril-mai. Comment savoir qu'on a une allergie aux pollens? Quels gestes peuvent soulager au quotidien? Quels traitements existent?
                Laissez-nous vos questions ou témoignages par téléphone en direct au 0800 108 208, par message sur Whatsapp, Telegram, Signal et Threema au 076 206 12 12, ou encore par email à l'adresse courriel onenparle@rts.ch. Des spécialistes vous répondent mardi 23 mai, dès 9h07.",
            'image' => 'https://www.rts.ch/2023/05/22/11/20/14009505.image?&w=1630&h=750',
            'broadcast_date' => '2023-05-22',
            'audio_file' => 'https://rts-aod-dd.akamaized.net/ww/14008725/cd93330a-b850-3c18-83c0-62a47f0c5312.mp3',     
        ]);
        DB::table('chat_rooms')->insert([
            'title' => 'Wanted: le moustique tigre',
            'description' => "Après Genève et le Valais, le moustique tigre s’est installé dans le canton de Vaud. Pour limiter son expansion, les autorités appellent le public à signaler sa présence et à éliminer les lieux de ponte potentiels à domicile. 
            Quels sont les gestes les plus efficaces?
            ",
            'image' => 'https://www.rts.ch/2023/05/31/10/51/14063700.image?&w=1413&h=650',
            'broadcast_date' => '2023-06-01',
            'audio_file' => 'https://rts-aod-dd.akamaized.net/ww/14033607/b2751e58-842e-3953-a029-325f0f6dd589.mp3',     
        ]);
    }
}
