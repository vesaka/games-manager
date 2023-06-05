<?php

namespace Vesaka\Games\DB\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Vesaka\Games\Catalogue\BaseGame;
use Vesaka\Games\Models\Game;
use Vesaka\Games\Models\GameSession;
use Vesaka\Games\Models\Player;

/**
 * Description of UnblockmeGameSessionsSeeder
 *
 * @author vesak
 */
class UnblockmeGameSessionsSeeder extends Seeder {
    use WithoutModelEvents;

    public function run() {
        $game = Game::firstOrCreate([
            'author_id' => 1,
            'content' => 'lorem ispum',
            'title' => 'UnblockMe',
            'name' => 'unblockme',
            'status' => 'active',
            'parent' => 0,
        ]);

        for ($i = 0; $i < 20; $i++) {
            $player = Player::factory()->create();
            $randomSessionCount = fake()->numberBetween(1, 100);
            for ($j = 0; $j < $randomSessionCount; $j++) {
                $startAt = fake()->dateTimeBetween('-1 month', '-1 hour');
                GameSession::insert([
                    'game_id' => $game->id,
                    'user_id' => $player->id,
                    'started_at' => $startAt,
                    'ended_at' => fake()->dateTimeBetween($startAt, '+1 hour'),
                    'score' => fake()->numberBetween(1, 3),
                    'payload' => json_encode([
                        'level' => fake()->numberBetween(1, 15),
                        'moves' => fake()->numberBetween(5, 75),
                    ]),
                    'info' => json_encode((new BaseGame())->fetchUserAgentInfo()),
                    'status' => GameSession::COMPLETED,
                ]);
            }
        }
    }
}
