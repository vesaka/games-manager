<?php

namespace Vesaka\Games\Tests\Feature\Player;

use Tests\TestCase;
use Vesaka\Games\Tests\Traits\{
    BindsGameSessionRepository, 
    WithJsonValidationInput
};
use Vesaka\Games\Models\Player;

/**
 * Description of FrogotPasswordTest
 *
 * @author vesak
 */
class FrogotPasswordTest extends TestCase {
    use BindsGameSessionRepository, WithJsonValidationInput;
    
    const ROUTE_NAME = 'game::forgot-password';
    
    public function testForgotPassworFormRequiresEmail() {
       $this->postInvalidData(self::ROUTE_NAME, [
            'email' => null
        ]);
    }
    
    public function testForgotPassworFormRequiresValidEmail() {
       $this->postInvalidData(self::ROUTE_NAME, [
            'email' => 'not-an-email'
        ]);
    }
    
    public function testForgotPassworFormChecksIfEmailExists() {
       $this->postInvalidData(self::ROUTE_NAME, [
            'email' => 'test@example.org'
        ]);
    }
    
    public function testForgotPassworAcceptsPasswordResetRequest() {
       $player = Player::factory()->create();
       $this->postValidData(self::ROUTE_NAME, [
            'email' => $player->email
        ]);
    }
}
