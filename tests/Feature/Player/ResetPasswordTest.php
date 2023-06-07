<?php

namespace Vesaka\Games\Tests\Feature\Player;

use Tests\TestCase;
use Vesaka\Games\Tests\Traits\{
    BindsGameSessionRepository, 
    WithJsonValidationInput
};
use Vesaka\Games\Models\Player;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

/**
 * Description of ResetPasswordTest
 *
 * @author vesak
 */
class ResetPasswordTest extends TestCase {
    use BindsGameSessionRepository, WithJsonValidationInput;
    
    const ROUTE_NAME = 'game::reset-password';
    
    public function testResetPassworFormRequiresEmail() {
       $this->postInvalidData(self::ROUTE_NAME, [
            'email' => null
        ]);
    }
    
    public function testResetPassworFormRequiresValidEmail() {
       $this->postInvalidData(self::ROUTE_NAME, [
            'email' => 'not-an-email'
        ]);
    }
    
    public function testResetPassworFormRequiresPassword() {
       $this->postInvalidData(self::ROUTE_NAME, [
            'password' => null
        ]);
    }
    
    public function testResetPassworFormRequiresToken() {
       $this->postInvalidData(self::ROUTE_NAME, [
            'token' => null
        ]);
    }

    public function testResetPasswordFormRejectsInvalidOrExpiredTokens() {
        $user = Player::factory()->create();
        $token = Password::broker()->createToken($user);
        Password::broker()->deleteToken($user);
        $password = Str::random(10);
        $this->postInvalidData(self::ROUTE_NAME, [
                'email' => $user->email,
                'token' => $token,
                'password' => $password,
                'password_confirmation' => $password
        ]);
    }
    
    public function testResetPasswordFormChecksIfEmailExists() {
       $this->postInvalidData(self::ROUTE_NAME, [
            'email' => 'test@example.org'
        ]);
    }

    public function testResetPasswordFormRequiresConfirmedPassword() {
       $this->postInvalidData(self::ROUTE_NAME, [
            'password' => 'password',
            'password_confirmation' => 'not-password'
        ]);
    }

    public function testResetPasswordFormRequiresPasswordLength() {
       $this->postInvalidData(self::ROUTE_NAME, [
            'password' => 'pass',
            'password_confirmation' => 'pass'
        ]);
    }

    public function testResetPasswordFormRejectsLongPasswords() {
        $password = Str::random(256);
        $this->postInvalidData(self::ROUTE_NAME, [
                'password' => $password,
                'password_confirmation' => $password
        ]);
    }
   
    public function testUserResetsTheirPasswordSuccessfully() {
        $user = Player::factory()->create();
        $token = Password::broker()->createToken($user);
        $password = Str::random(10);
        $this->postValidData(self::ROUTE_NAME, [
                'email' => $user->email,
                'token' => $token,
                'password' => $password,
                'password_confirmation' => $password
        ]);

        $this->assertCredentials([
                'email' => $user->email,
                'password' => $password
        ]);

    }
    
}
