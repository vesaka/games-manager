<?php

namespace Vesaka\Games\DB\Seeders;

use Illuminate\Database\Seeder;
use Vesaka\Games\Models\Player;

class PlayersSeeder extends Seeder {
    
    public function run() {
        Player::factory()
                ->count(5)
                ->hasGameSessions(fake()->numberBetween(3, 10))
                ->create();

    }

}
