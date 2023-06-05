<?php

namespace Vesaka\Games\Tests\Integration\Games;

use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use Vesaka\Games\Tests\Traits\BindsGameSessionRepository;

/**
 * Description of UnblockmeGameSessionTest
 *
 * @author vesak
 */
class UnblockmeGameSessionTest extends TestCase {
    use BindsGameSessionRepository;

    protected function setUp(): void {
        parent::setUp();
        $this->bindGameSessionAlias();
        Sanctum::actingAs(auth()->user());
    }

    public function test_start_unblockme_request_returns_200() {
        $response = $this->withHeaders([
            HEADER_GAME_NAME => 'unblockme',
        ])->json('post', '/api/play/start');

        $response->assertStatus(200);
    }

//    public function test_end_unblockme_request_returns_200() {
//        //$mockGameSession =
//    }
}
