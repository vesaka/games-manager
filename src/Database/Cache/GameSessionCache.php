<?php

namespace Vesaka\Games\Database\Cache;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Vesaka\Core\Abstracts\BaseCache;
use Vesaka\Games\Database\Interfaces\GameSessionInterface;
use Vesaka\Games\Models\GameSession;

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
}
