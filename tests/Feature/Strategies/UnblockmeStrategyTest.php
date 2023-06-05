<?php

namespace Vesaka\Games\Tests\Feature\Strategies;

use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Request;
use Tests\TestCase;
use Vesaka\Games\Catalogue\Unblockme;
use Vesaka\Games\DB\Seeders\UnblockmeGameSessionsSeeder;
use Vesaka\Games\Tests\Traits\BindsGameSessionRepository;

/**
 * Description of UnblockmeStrategyTest
 *
 * @author vesak
 */
class UnblockmeStrategyTest extends TestCase {
    use BindsGameSessionRepository;

    protected function setUp(): void {
        parent::setUp();
        $this->bindGameSessionAlias();
    }

    public function test_unblockme_gets_top_scores() {
        $this->artisan('db:seed', [
            '--class' => UnblockmeGameSessionsSeeder::class,
        ]);

        $mockRequest = new HttpRequest();
        $mockRequest->headers->set(HEADER_GAME_NAME, 'unblockme');
        Request::swap($mockRequest);

        $strategy = new Unblockme();

        $limit = 10;
        $topScores = $strategy->getRanking($limit);
        $currentScore = $topScores->get(0)->score;

        $this->assertLessThanOrEqual($limit, $topScores->count());

        $topScores->each(function ($game) use ($currentScore) {
            $this->assertIsInt($game->score);
            $this->assertLessThanOrEqual($currentScore, $game->score);
        });
    }
}
