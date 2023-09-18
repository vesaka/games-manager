<?php

namespace Vesaka\Games\Database\Interfaces;

use Vesaka\Core\Abstracts\BaseInterface;
use Vesaka\Games\Models\{Player, GameSession};
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

/**
 *
 * @author Vesaka
 */
interface GameSessionInterface extends BaseInterface {
    
    public function start(Request $request): GameSession;
    
    public function end(Request $request): GameSession;
    
    public function getRankings(): Collection;

    public function transfer(Player $guest, Player $player): void;
    
}

