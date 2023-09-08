<?php

namespace Vesaka\Games\Database\Repositories;

use Vesaka\Core\Abstracts\BaseRepository;
use Vesaka\Games\Models\GameSession;
use Illuminate\Support\Collection;
use Vesaka\Games\Database\Interfaces\GameSessionInterface;
use Illuminate\Http\Request;
use Vesaka\Games\Catalogue\BaseGame;
use Vesaka\Games\Contracts\GameHandlerContract;

/**
 * Description of GameRepository
 *
 * @author Vesaka
 */
class GameSessionRepository extends BaseRepository implements GameSessionInterface {

    protected static $strategy;
    
    public function start(Request $request): GameSession {
        return self::getStrategy()->begin($request);
    }

    public function end(Request $request): GameSession {
        return self::getStrategy()->save($request);
    }

    public function getRankings(): Collection {
        return self::getStrategy()->getRanking();
    }
    
    protected static function getStrategy(): GameHandlerContract {
        if (!self::$strategy) {
            $gameKey = static::getGameSlug(); 
            $gameStrategy = config('games.catalogue.' . $gameKey . '.strategy');
            if (!is_string($gameStrategy) || !class_exists($gameStrategy)) {
                $gameStrategy = BaseGame::class;
            }
            self::$strategy = new $gameStrategy();            
        }
        return self::$strategy;
    }
    
    protected static function getGameSlug(): string {
        $gameKey = request()->header(HEADER_GAME_NAME);
        if (!$gameKey) {
            $gameKey = request()->get(config('games.identifier', ''));
        }
        return $gameKey ?? 'default';
    }
    

}
