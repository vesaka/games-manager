<?php

namespace Vesaka\Games\Database\Cache;
use Vesaka\Core\Abstracts\BaseCache;
use Vesaka\Core\Abstracts\BaseRepository;
use Vesaka\Games\Models\GameSession;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Vesaka\Games\Database\Interfaces\GameSessionInterface;

/**
 * Description of GameSessionCache
 *
 * @author Vesaka
 */
class GameSessionCache extends BaseCache implements GameSessionInterface  {
    
    public function start(Request $request): GameSession {
        return $this->raw();
       // return $this->tags('games', "game:$request->gid", "game_session:$request->sid");
    }
    
    public function end(\Illuminate\Http\Request $request): GameSession {
        return $this->raw();
    }
 
    public function getRankings(): Collection {
        return $this->raw();
    }

}
