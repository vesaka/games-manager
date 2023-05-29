<?php

namespace Vesaka\Games\Tests\Feature\Strategies;

use Vesaka\Games\Catalogue\BaseGame;
use App\Models\User;
use Illuminate\Http\Request;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Auth;
use Vesaka\Games\Models\Game;
use Vesaka\Games\Models\GameSession;
use Vesaka\Games\DB\Seeders\PlayersSeeder;
use Vesaka\Games\Tests\Traits\BindsGameSessionRepository;

/**
 * Description of BaseGamemeStrategyTest
 *
 * @author vesak
 */
class BaseGameStrategyTest extends TestCase {

    use WithFaker,
        RefreshDatabase,
        BindsGameSessionRepository;
    
    protected Game $game;
    
    protected function setUp(): void {
        parent::setUp();
        // Mock the authenticated user
        $user = User::factory()->create();
        $this->actingAs($user);
        
        if (!defined('HEADER_GAME_NAME')) {
            define('HEADER_GAME_NAME', 'X-Game-Type');
        }
        
        // Set the session key configuration
        Config::set('games.session_key', 'session_id');
        Config::set('games.identifier', '_gk');
        $this->bindGameSessionAlias();
        
        //dd(app('game.session')->getGameSlug());
        
        $this->game = new Game;
        $this->game->name = 'default';
        $this->game->title = 'Default';
        $this->game->type = 'type';
        $this->game->status = 'active';
        $this->game->content = 'lorem ipsum';
        $this->game->parent = 0;
        $this->game->author_id = $user->id;
        $this->game->saveQuietly();
        $this->game->name = 'default';
        $this->game->saveQuietly();

    }

    public function test_startegy_starts_game_session() {
        $request = $this->mockRequest([
            'game_id' => $this->game->id
        ]);
       
        $strategy = new BaseGame;
        $gameSession = $strategy->begin($request);
        $this->assertInstanceOf(GameSession::class, $gameSession);
        $this->assertNotNull($gameSession->user_id);
        $this->assertNotNull($gameSession->started_at);
        $this->assertNotEmpty($gameSession->info);      
        
    }
    
    
    public function test_startegy_saves_game_session() {
        $score = 250;
        $strategy = new BaseGame();
        $mockedGameSession = $strategy->begin($this->mockRequest([
            'game_id' => $this->game->id,
            'sid' => 1,
        ]));
        $gameKey = config('games.session_key');
        
        $request = $this->mockRequest([
            'score' => $score,
            $gameKey => $mockedGameSession->id,
            'game_id' => $this->game->id,
        ]);
        
        $gameSession = $strategy->save($request);
        $this->assertInstanceOf(GameSession::class, $gameSession);
        $this->assertEquals($request->score, $gameSession->score);
        $this->assertNotNull($gameSession->ended_at);
        
        
    }
    
    public function test_startegy_gets_top_scores() {
        $this->artisan('db:seed', [
            '--class' => PlayersSeeder::class
        ]);
        
        $strategy = new BaseGame();
        
        $limit = 10;
        
        $topScores = $strategy->getRanking(10);
        $currentScore = $topScores->get(0)->score;
        
        $this->assertLessThanOrEqual($limit, $topScores->count());
        
        $topScores->each(function($game) use ($currentScore) {
            $this->assertIsInt($game->score);
            $this->assertIsString($game->top_scores);
            $this->assertLessThanOrEqual($currentScore, $game->score);
            
            $bestScores = collect(explode(',', $game->top_scores));
            $this->assertGreaterThanOrEqual(1, $bestScores->count());
            
            $currentScore = $game->score;
            
            $currentBestScore = $game->score;
            $bestScores->each(function($score) use ($currentBestScore) {
                $this->assertLessThanOrEqual($currentBestScore, (int) $score);
                $currentBestScore = (int) $score;
            });
            
            
        });
        
    }
    
    protected function mockRequest(array $data = []): Request
    {
        $request = Request::create('/', 'POST');
        $request->header('X-Game-Type', 'default');
        $request->merge($data);
        $request->setUserResolver(function () {
            return Auth::user();
        });
        return $request;
    }

}
