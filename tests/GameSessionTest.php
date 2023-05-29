<?php

namespace Vesaka\Games\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

/**
 * Description of GameSessionTest
 *
 * @author vesak
 */
class GameSessionTest extends TestCase {

    use WithFaker,
        RefreshDatabase;

//    public function test_if_game_starts() {
//
//        Sanctum::actingAs(User::factory()->create());
//        $response = $this->json('post', '/api/play/start?_gk=unblockme');
//
//        $response->assertStatus(200);
//    }

}
