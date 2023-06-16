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
            'title' => "Guichet : la forêt",
            'description' => "Sapins, hêtres ou bouleaux… Un tiers du territoire suisse est recouvert de forêts. Forêts de résineux et de feuillus, forêts publiques et privées (plus de 250'000 propriétaires)... La forêt qui est l'un des espaces de loisir et de détente préférés des suisses et suissesses. Quelle est votre coin de forêt préféré? Quel rituel effectuez-vous toujours en forêt? Quel est votre son préféré sous les grands arbres?",
            'image' => "https://www.rts.ch/2023/05/23/11/08/14044011.image?&w=1630&h=750",
            'broadcast_date' => "2023-05-30",
            'audio_file' => "https://rts-aod-dd.akamaized.net/ww/14026958/a48d4e42-9fc1-316d-a5b3-28c8c008c872.mp3",
            'on_air' => false,
            'closed' => false,
        ]);
        DB::table('chat_rooms')->insert([
            'title' => "Guichet : voyager autrement",
            'description' => "Comment voyager mieux en polluant moins ? Vous avez changé votre manière de voyager ? Vous avez des expériences en tourisme durable à partager? Des idées à nous donner ? Des questions à poser?",
            'image' => "https://www.rts.ch/2023/05/29/11/18/14058815.image?&w=1630&h=750",
            'broadcast_date' => "2023-06-06",
            'audio_file' => "https://rts-aod-dd.akamaized.net/ww/14043406/9858fc3f-9676-3f10-a85d-5be9586f0b5f.mp3",
            'on_air' => false,
            'closed' => false,
        ]);
        DB::table('chat_rooms')->insert([
            'title' => "Guichet : la mobilité électrique",
            'description' => "Comment choisir un véhicule électrique? Comment installer une borne chez soi?",
            'image' => "https://www.rts.ch/2023/06/12/10/26/14094930.image?&w=1630&h=750",
            'broadcast_date' => "2023-06-14",
            'audio_file' => "https://rts-aod-dd.akamaized.net/ww/14063157/bca45c3b-14f2-3b75-a859-c4d196318a14.mp3",
            'on_air' => false,
            'closed' => false,
        ]);
        DB::table('chat_rooms')->insert([
            'title' => "Guichet : le surendettement",
            'description' => "Que ce soit suite à des crédits accumulés, des difficultés financières ou des imprévus de la vie, le surendettement peut avoir un impact considérable sur notre bien-être et notre stabilité financière.",
            'image' => "https://www.rts.ch/2023/06/13/08/08/14097831.image?&w=1630&h=750",
            'broadcast_date' => "2023-06-20",
            'audio_file' => "",
            'on_air' => false,
            'closed' => false,
        ]);
    }
}
