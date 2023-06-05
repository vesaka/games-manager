<?php

namespace Vesaka\Games\Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

/**
 * Description of GameSessionTest
 *
 * @author vesak
 */
class GameSessionTest extends TestCase {
    use WithFaker;
    use RefreshDatabase;

    //    public function test_if_game_starts() {
    //
    //        Sanctum::actingAs(User::factory()->create());
    //        $response = $this->json('post', '/api/play/start?_gk=unblockme');
    //
    //        $response->assertStatus(200);
    //    }
}
