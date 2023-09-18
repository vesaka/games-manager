<?php

namespace Vesaka\Games\Database\Cache;
use Vesaka\Core\Abstracts\BaseCache;
use Vesaka\Games\Models\{Player, GameSession};
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Vesaka\Games\Database\Interfaces\GameSessionInterface;

/**
 * Description of GameSessionCache
 *
 * @author Vesaka
 */
class GameSessionCache extends BaseCache implements GameSessionInterface {
    
    public function start(Request $request): GameSession {
        return $this->raw();
       // return $this->tags('games', "game:$request->gid", "game_session:$request->sid");
    }
    
    public function end(Request $request): GameSession {
        return $this->raw();
    }
 
    public function getRankings(): Collection {
        return $this->raw();
    }

    public function transfer(Player $guest, Player $player): void {
        $this->raw();
    }
    
}
