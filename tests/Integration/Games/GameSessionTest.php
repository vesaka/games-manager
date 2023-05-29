<?php

namespace Vesaka\Games\Tests\Integration\Games;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\Config;

/**
 * Description of GameSessionTest
 *
 * @author vesak
 */
class GameSessionTest extends TestCase {

    use WithFaker,
        RefreshDatabase;
    
    protected function setUp(): void {
        parent::setUp();
        // Mock the authenticated user
        $user = User::factory()->create();
        $this->actingAs($user);
        
        
        
        // Set the session key configuration
//        Config::set('games.identifier', '_gk');
//        Config::set('games.session_key', 'session_id');
    }
    
    public function test_games_config_file_exists() {
        $this->assertFileExists(__DIR__ . '/../../../config/games.php');
    }

    public function test_start_game_session_request_returns_200() {
        $this->setConfigFile();
        
        Sanctum::actingAs(User::factory()->create());
        $response = $this->json('post', '/api/play/start?_gk=unblockme');

        $response->assertStatus(200);
    }
    
    protected function setConfigFile() {
        $gamesConfig = require __DIR__ . '/../../../config/games.php';
        Config::set('games', $gamesConfig);
    }

}
