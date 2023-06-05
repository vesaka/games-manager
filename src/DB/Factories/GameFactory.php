<?php

namespace Vesaka\Games\DB\Factories;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Factories\Factory;
use Vesaka\Games\Models\Game;

/**
 * Description of GameFactory
 *
 * @author vesak
 */
class GameFactory extends Factory {
    use WithoutModelEvents;

    protected $model = Game::class;

    public function definition() {
        return [
            'author_id' => 1,
            'title' => 'Default Game',
            'name' => 'default',
            'content' => 'lorem ipsum',
            'type' => 'game',
            'status' => 'active',
            'parent' => 0,
        ];
    }
}
