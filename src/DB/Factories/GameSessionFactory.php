<?php
namespace Vesaka\Games\DB\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Vesaka\Games\Models\GameSession;
use Vesaka\Games\Catalogue\BaseGame;

/**
 * Description of GameSessionFactory
 *
 * @author vesak
 */
class GameSessionFactory extends Factory {
    
    protected $model = GameSession::class;
    //put your code here
    public function definition() {
        
        $startAt = fake()->dateTimeBetween('-1 month', '-1 hour');
        return [
            'game_id' => 1,
            'started_at' => $startAt,
            'ended_at' => fake()->dateTimeBetween($startAt, '+1 hour'),
            'score' => fake()->randomNumber(4),
            'payload' => [],
            'info' => (new BaseGame)->fetchUserAgentInfo(),
            'status' => GameSession::COMPLETED
            
        ];
    }

}
