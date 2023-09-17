<?php

namespace Vesaka\Games\Catalogue;

use DB;
use Illuminate\Support\Collection;
use Vesaka\Games\Models\GameSession;
use Illuminate\Http\Request;

class MemoryGame extends BaseGame {

    public function calculate(Request $request, GameSession $gameSession): int {
        $data = $gameSession->getData(true);
        if (! $data || ! isset($data['levels'])) {
            return 0;
        }
        list($time, $moves) = array_reduce($data['levels'], function($acc, $level) {
            $acc[0] += $level['time'];
            $acc[1] += $level['moves'];
            return $acc;
        }, [0, 0]);
        return round(pow((pow(1 / $time, 2) * (1 / $moves)), 0.2) * 10000000);
    }
}