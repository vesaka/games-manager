<?php

namespace Vesaka\Games\Tests\Integration\Games;

use Illuminate\Support\Facades\Config;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use Vesaka\Games\Tests\Traits\BindsGameSessionRepository;

/**
 * Description of GameSessionTest
 *
 * @author vesak
 */
class GameSessionTest extends TestCase {
    use BindsGameSessionRepository;

    protected function setUp(): void {
        parent::setUp();
        $this->bindGameSessionAlias();
    }

    public function test_games_config_file_exists() {
        $this->assertFileExists(__DIR__.'/../../../config/games.php');
    }

    public function test_start_game_session_request_returns_200() {
        $this->setConfigFile();

        Sanctum::actingAs(auth()->user());
        $response = $this->json('post', '/api/play/start');

        $response->assertStatus(200);
    }

    protected function setConfigFile() {
        $gamesConfig = require __DIR__.'/../../../config/games.php';
        Config::set('games', $gamesConfig);
    }
}
