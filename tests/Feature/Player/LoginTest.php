<?php

namespace Vesaka\Games\Tests\Feature\Player;

use Tests\TestCase;
use Vesaka\Games\Tests\Traits\{
    BindsGameSessionRepository, 
    WithJsonValidationInput
};
use Vesaka\Games\Models\Player;

/**
 * Description of LoginTest
 *
 * @author vesak
 */
class LoginTest extends TestCase {
    
    use BindsGameSessionRepository, WithJsonValidationInput;
    
    const ROUTE_NAME = 'game::login';
    
    protected function setUp(): void {
        parent::setUp();
        $this->bindGameSessionAlias();
    }
    
    public function testLoginFormRequiresEmail() {
       $this->postInvalidData(self::ROUTE_NAME, [
            'email' => null
        ]);
    }
    
    public function testLoginFormRequiresPassword() {
       $this->postInvalidData(self::ROUTE_NAME, [
            'password' => null
        ]);
    }
    
    public function testLoginFormRequiresValidEmail() {
       $this->postInvalidData(self::ROUTE_NAME, [
            'email' => 'not-an-email'
        ]);
    }
    
    public function testLoginFormpRejectsInvalidCredentials() {
       $this->postInvalidData(self::ROUTE_NAME, [
            'email' => 'user@example.org',
            'password' => '2039jf34dfj'
        ]);
    }
    
    public function testLoginFormRejectsInvalidCredentials() {
        $password = '12345678';
        $player = Player::factory()->create([
            'email' => 'test@example.org',
            'password' => bcrypt($password)
        ]);
        
        $this->postInvalidData(self::ROUTE_NAME, [
            'email' => $player->email,
            'password' => 'not-same-password'
        ]);
        
        $this->artisan('migrate:refresh');
    }
    
    public function testPlayerShouldLogInSuccesfully() {
        $password = '12345678';
        $player = Player::factory()->create([
            'password' => bcrypt($password)
        ]);
        
        $response = $this->postValidData(self::ROUTE_NAME, [
            'email' => $player->email,
            'password' => $password
        ]);
        
        $response->assertJsonPath('id', $player->id);
        $response->assertJsonPath('name', $player->name);
        
        $this->artisan('migrate:refresh');
    }
       
}
