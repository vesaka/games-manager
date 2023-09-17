<?php

namespace Vesaka\Games\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Encryption\Encrypter;
/**
 * Description of GameSessionController
 *
 * @author vesak
 */
class GameSessionController extends Controller {

    public function start(Request $request) {
        return app('game.session')
            ->start($request)
            ->only('id', 'game_id', 'hash', 'status');
    }

    public function end(Request $request) {
        return app('game.session')
            ->end($request)
            ->only('id', 'game_id', 'status');
    }

    public function leaderboard(Request $request) {
        return app('game.session')->getRankings($request);
    }

    public function testEncryption(Request $request) {
        return (new Encrypter($request->secret, 'aes-256-cbc'))->decrypt($request->payload, false);
    }
}
