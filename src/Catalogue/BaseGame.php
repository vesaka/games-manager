<?php

namespace Vesaka\Games\Catalogue;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Jenssegers\Agent\Agent;
use Vesaka\Games\Contracts\GameHandlerContract;
use Vesaka\Games\Models\{Game, GameSession};
use Illuminate\Support\Str;

/**
 * Description of BaseGame
 *
 * @author vesak
 */
class BaseGame implements GameHandlerContract {

    protected bool $allowsGuest = false;
    final public static function getGameId(): int {
        $gameKey = request()->header(HEADER_GAME_NAME);
        if (! $gameKey) {
            $gameKey = request()->get(config('games.identifier', '')) ?? 'default';
        }

        $game = Game::select('id')
            ->where('name', $gameKey)
            ->first();

        return $game ? $game->id : 0;
    }

    public function begin(Request $request): GameSession {
        $gameSession = new GameSession();
        $gameSession->game_id = self::getGameId();
        $gameSession->user_id = $request->user()->id;
        $gameSession->started_at = now();
        $gameSession->info = $this->getUserAgentInfo();
        $gameSession->hash =  Str::random(32);
        $gameSession->status = GameSession::ACTIVE;
        $gameSession->save();

        return $gameSession;
    }

    public function save(Request $request): GameSession {
        $gameSession = GameSession::where('id', $request->get(config('games.session_key')))
            ->where('user_id', $request->user()->id)
            ->where('game_id', self::getGameId())
            ->first();
        $gameSession->payload = $request->payload ?? [];
        $gameSession->ended_at = now();
        $gameSession->score = $this->calculate($request, $gameSession);
        $gameSession->status = GameSession::COMPLETED;
        
        $gameSession->save();

        return $gameSession;
    }

    public function cancel(Request $request): GameSession {
        $gameSession = GameSession::where('id', $request->get(config('games.session_key')))
            ->where('user_id', $request->user()->id)
            ->where('game_id', self::getGameId())
            ->first();

        $gameSession->ended_at = now();
        $gameSession->status = GameSession::FAILED;
        $gameSession->save();

        return $gameSession;
    }

    public function calculate(Request $request, GameSession $gameSession): int|float {
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
                )->groupBy('user_id'),
                'best',
                'best.user_id',
                '=',
                'gs.user_id'
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

    final protected function getUserAgentInfo(): array {
        $agent = new Agent();
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
            'device' => $agent->device(),
        ];
    }

    public function fetchUserAgentInfo(): array {
        return $this->getUserAgentInfo();
    }

    public function decrypt(Request $request): array {
        if (! $request->payload) {
            return [];
        }


        //return decrypt();
    }

}
