<?php

namespace Vesaka\Games\DB\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Vesaka\Games\Models\Player;
use Vesaka\Games\Models\GameSession;

class PlayersSeeder extends Seeder {
    
    public function run() {
        Player::factory()
                ->count(5)
                ->hasGameSessions(fake()->numberBetween(3, 10))
                ->create();

    }

}
