<?php

namespace Vesaka\Games\Contracts;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Vesaka\Games\Models\GameSession;

/**
 * @author vesak
 */
interface GameHandlerContract {
    //put your code here

    public function begin(Request $request): GameSession;

    public function save(Request $request): GameSession;

    public function calculate(Request $request, GameSession $gameSession): int|float;

    public function getRanking(int $limit = 10): Collection;
}
