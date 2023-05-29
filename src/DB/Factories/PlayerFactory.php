<?php

namespace Vesaka\Games\DB\Factories;

use Vesaka\Games\Models\Player;
use Database\Factories\UserFactory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Description of PlayerFactory
 *
 * @author vesak
 */
class PlayerFactory extends UserFactory {
    
    protected $model = Player::class;

    public function definition() {
        return [
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'password' => Hash::make('123456'),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ];
    }

}
