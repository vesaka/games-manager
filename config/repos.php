<?php

use Vesaka\Games\Database\Cache;
use Vesaka\Games\Database\Interfaces;
use Vesaka\Games\Database\Repositories;
use Vesaka\Games\Models;
use Vesaka\Games\Observers;
use Vesaka\Games\Policies;

return [
    'game' => [
        'model' => Models\Game::class,
        'repository' => Repositories\GameRepository::class,
        'cache' => Cache\GameCache::class,
        'interface' => Interfaces\GameInterface::class,
        'observer' => Observers\GameObserver::class,
        'policy' => Policies\GamePolicy::class,
    ],
    'game.session' => [
        'model' => Models\GameSession::class,
        'repository' => Repositories\GameSessionRepository::class,
        'cache' => Cache\GameSessionCache::class,
        'interface' => Interfaces\GameSessionInterface::class,
        'observer' => Observers\GameSessionObserver::class,
        'policy' => Policies\GameSessionPolicy::class,
    ],
];
