<?php

namespace Vesaka\Games\Catalogue;

use Vesaka\Games\Models\GameSession;
use Illuminate\Support\Collection;
use DB;

/**
 * Description of Unblockme
 *
 * @author vesak
 */
class Unblockme extends BaseGame {
    
    public function getRanking(int $limit = 10): Collection {
        $results = GameSession::from('game_sessions as gs')
                ->select('gs.user_id', 'payload->level as level', DB::raw('CAST(SUM(best.score) AS UNSIGNED) as score'))
                ->joinSub(
                    GameSession::select(
                            'user_id',
                            DB::raw('MAX(score) as score'),
                            'payload->level as level'
                    )
                    ->groupBy('user_id')
                    ->groupBy('payload->level'),
                    'best', 'best.user_id', '=', 'gs.user_id'
                )
                ->with('player:id,name')
                ->where('gs.status', GameSession::COMPLETED)
                ->whereColumn('payload->level', '=', 'best.level')
                ->orderBy('score', 'desc')
                ->groupBy('gs.user_id')
                ->groupBy('payload->level')
                ->where('game_id', self::getGameId())
                ->limit($limit)
                ->get();

        return $results;
    }
}
