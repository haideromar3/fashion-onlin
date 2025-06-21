<?php

namespace Database\Seeders;

use App\Models\VirtualCard;
use Illuminate\Database\Seeder;

class VirtualCardSeeder extends Seeder
{
    public function run()
    {
        $cards = [
            ['user_id' => 1, 'card_number' => 'CARD1001', 'balance' => 100],
            ['user_id' => 1, 'card_number' => 'CARD1002', 'balance' => 150],
            ['user_id' => 1, 'card_number' => 'CARD1003', 'balance' => 200],
            ['user_id' => 1, 'card_number' => 'CARD1004', 'balance' => 80],
            ['user_id' => 1, 'card_number' => 'CARD1005', 'balance' => 120],
        ];

        foreach ($cards as $card) {
            VirtualCard::updateOrCreate(
                ['card_number' => $card['card_number']],
                $card
            );
        }
    }
}




//php artisan db:seed