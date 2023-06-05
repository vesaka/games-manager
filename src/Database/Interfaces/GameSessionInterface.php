<?php

namespace Vesaka\Games\Database\Interfaces;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Vesaka\Core\Abstracts\BaseInterface;
use Vesaka\Games\Models\GameSession;

/**
 * @author Vesaka
 */
interface GameSessionInterface extends BaseInterface {
    public function start(Request $request): GameSession;

    public function end(Request $request): GameSession;

    public function getRankings(): Collection;
}
