<?php

namespace Vesaka\Games\Tests\Traits;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Vesaka\Games\Database\Interfaces\GameSessionInterface;
use Vesaka\Games\Database\Repositories\GameSessionRepository;
use Vesaka\Games\Models\GameSession;

/**
 * Description of BindsGameSessionRepository
 *
 * @author vesak
 */
trait BindsGameSessionRepository {
    use WithFaker;
    use RefreshDatabase;

    public function bindGameSessionAlias() {
        if (! defined('HEADER_GAME_NAME')) {
            define('HEADER_GAME_NAME', 'X-Game-Type');
        }

        $user = User::factory()->create();
        $this->actingAs($user);
        Config::set('games.session_key', 'session_id');
        Config::set('games.identifier', '_gk');
        app()->bind(GameSessionInterface::class, function () {
            return new GameSessionRepository(new GameSession());
        });

        app()->alias(GameSessionInterface::class, 'game.session');
    }

    protected function mockRequest(array $data = [], array $headers = []): Request {
        $request = Request::create('/', 'POST');

        //        $request->header('X-Game-Type', 'unblockme');

        foreach ($headers as $key => $value) {
            $request->header($key, $value);
        }

        $request->merge($data);
        $request->setUserResolver(function () {
            return Auth::user();
        });

        return $request;
    }
}
