<?php

namespace Vesaka\Games\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Description of GameSessionController
 *
 * @author vesak
 */
class GameSessionController extends Controller {
    
    public function start(Request $request) {
        return app('game.session')->start($request);
    }
    
    public function end(Request $request) {
        return app('game.session')->end($request);
    }
    
    public function leaderboard(Request $request) {
        return app('game.session')->getRankings($request);
    }
}
