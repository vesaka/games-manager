<?php
namespace Vesaka\Games\Catalogue;

use Vesaka\Games\Contracts\GameHandlerContract;
use Jenssegers\Agent\Agent;
use Vesaka\Games\Models\GameSession;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use DB;
/**
 * Description of BaseGame
 *
 * @author vesak
 */
class BaseGame implements GameHandlerContract {
    
    
    public function begin(Request $request): GameSession {
        $gameSession = new GameSession;
        $gameSession->user_id = $request->user()->id;
        $gameSession->started_at = now();
        $gameSession->payload = [];
        $gameSession->info = $this->getUserAgentInfo();
        $gameSession->save();
        return $gameSession;
    }

    public function save(Request $request): GameSession {
        $gameSession = GameSession::where('id', $request->get(config('games.session_key')))
                        ->where('user_id', $request->user()->id)
                        ->first();
        
        $gameSession->ended_at = now();
        $gameSession->score = $this->calculate($request);
        $gameSession->status = GameSession::COMPLETED;
        $gameSession->save();
        return $gameSession;
    }
    
    public function calculate(Request $request): int|float {
        return $request->score;
    }

    public function getRanking(int $limit = 10): Collection {
        $results = GameSession::from('game_sessions as gs')
                ->select('gs.user_id', 'gs.score', 'best.top_scores')
                ->joinSub(
                    GameSession::select(
                            'user_id',
                            DB::raw('MAX(score) as score'),
                            DB::raw('SUBSTRING_INDEX(GROUP_CONCAT(score ORDER BY CAST(score AS UNSIGNED) DESC), ",", 5) as top_scores')
                    )->groupBy('user_id'), 'best', 'best.user_id', '=', 'gs.user_id'
                )
                ->with('player:id,name')
                ->where('gs.status', GameSession::COMPLETED)
                ->whereColumn('gs.score', '=', 'best.score')
                ->orderBy('gs.score', 'desc')
                ->orderBy('best.top_scores')
                ->groupBy('gs.user_id')
                ->limit($limit)
                ->get();
        
        return $results;
    }
    
    protected final function getUserAgentInfo(): array {
        $agent = new Agent;
        $isMobile = $agent->isMobile();
        $isTablet = $agent->isTablet();
        $browser = $agent->browser();
        $platform = $agent->platform();
        
        return [
            'isPortable' => $isMobile || $isTablet,
            'isDesktop' => $agent->isDesktop(),
            'isPhone' => $agent->isPhone(),
            'isRobot' => $agent->robot(),
            'languages' => $agent->languages(),
            'platform' => $platform,
            'platform_version' => $agent->version($platform),
            'browser' => $browser,
            'browser_version' => $agent->version($browser),
            'ip' => request()->ip(),
            'device' => $agent->device()
        ];
    }
    
    public function fetchUserAgentInfo(): array {
        return $this->getUserAgentInfo();
    }

}
