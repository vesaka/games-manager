<?php

namespace Vesaka\Games\Database\Repositories;

use Vesaka\Core\Abstracts\BaseRepository;
use Vesaka\Games\Models\{Player, GameSession};
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
            $settings = array_merge(
                config('games.catalogue.default', []),
                config('games.catalogue.' . $gameKey, [])
            );
            if (!isset($settings['strategy']) && !class_exists(isset($settings['strategy']))) {
                $settings['strategy'] = BaseGame::class;
            }
            self::$strategy = new $settings['strategy']();            
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
    
    public function transfer(Player $guest, Player $player): void {
        $this->model->where('user_id', $guest->id)
            ->update(['user_id' => $player->id]);
    }


}
