<?php

namespace Vesaka\Games\Console\Commands\GameSessions;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Vesaka\Games\Models\GameSession;

class CalculateGameSessions extends Command {

    protected $signature = 'game-sessions:calculate {--game=}';

    protected $description = 'Calculate game sessions';

    public function handle() {

        
        $gameSessions = app('game.session')
        ->whereNotNull('payload')
        ->where('payload', '!=', '[]')
        ->whereHas('game', function(Builder $query) {
            $query->where('name', $this->option('game'));
        })->orderBy('id', 'desc')->limit(3)->get();

        $gameSessions->each(function(GameSession $gameSession) {
            dump($gameSession->getData(true));
            // $gameSession->score = app('game')->calculate($gameSession);
            // $gameSession->save();
        });
    }
}   