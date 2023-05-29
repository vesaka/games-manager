<?php

namespace Vesaka\Games\Tests\Feature\Strategies;

use Vesaka\Games\Catalogue\Unblockme;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Http\Request;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Auth;
use Vesaka\Games\Models\GameSession;
use Vesaka\Games\DB\Seeders\PlayersSeeder;

/**
 * Description of UnblockmeStrategyTest
 *
 * @author vesak
 */
class UnblockmeStrategyTest extends TestCase {

    use WithFaker,
        RefreshDatabase;
    
    protected function setUp(): void {
        parent::setUp();
        // Mock the authenticated user
        $user = User::factory()->create();
        $this->actingAs($user);
        
        // Set the session key configuration
        Config::set('games.session_key', 'session_id');
    }

    public function test_unblockme_starts_game_session() {
        $request = $this->mockRequest();
        
       
        $strategy = new Unblockme;
        $gameSession = $strategy->begin($request);
        $this->assertInstanceOf(GameSession::class, $gameSession);
        $this->assertNotNull($gameSession->user_id);
        $this->assertNotNull($gameSession->started_at);
        $this->assertNotEmpty($gameSession->info);      
        
    }
    
    
    public function test_unblockme_saves_game_session() {
        $score = 250;
        $strategy = new Unblockme();
        $mockedGameSession = $strategy->begin($this->mockRequest());
        $gameKey = config('games.session_key');
        
        $request = $this->mockRequest([
            'score' => $score,
            $gameKey => $mockedGameSession->id
        ]);
        
        $gameSession = $strategy->save($request);
        $this->assertInstanceOf(GameSession::class, $gameSession);
        $this->assertEquals($request->score, $gameSession->score);
        $this->assertNotNull($gameSession->ended_at);
        
        
    }
    
    public function test_unblockme_gets_top_scores() {
        $this->artisan('db:seed', [
            '--class' => PlayersSeeder::class
        ]);
        
        $strategy = new Unblockme();
        
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
        $request->merge($data);
        $request->setUserResolver(function () {
            return Auth::user();
        });
        return $request;
    }

}
