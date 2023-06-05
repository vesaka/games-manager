<?php

namespace Vesaka\Games\Tests\Feature\Player;

use Tests\TestCase;
use Vesaka\Games\Tests\Traits\{
    BindsGameSessionRepository, 
    WithJsonValidationInput
};
use Vesaka\Games\Models\Player;
use Illuminate\Support\Str;
/**
 * Description of RegsiterTest
 *
 * @author vesak
 */
class RegsiterTest extends TestCase {
    
    use BindsGameSessionRepository, WithJsonValidationInput;
    
    const ROUTE_NAME = 'game::register';
    
    public function testSignUpFormRequiresEmail(): void {
        $this->postInvalidData(self::ROUTE_NAME, [
            'email' => null
        ]);
    }
    
    public function testSignUpFormRequiresPassword(): void {
        $this->postInvalidData(self::ROUTE_NAME, [
            'password' => null
        ]);
    }
    
    public function testSignUpFormRequiresName(): void {
        $this->postInvalidData(self::ROUTE_NAME, [
            'name' => null
        ]);
    }
    
    public function testSignUpFormRequiresTermsToBeAccepted(): void {
        $this->postInvalidData(self::ROUTE_NAME, [
            'accept' => false
        ]);
    }
    
    public function testSignUpFormRequiresValidEmail(): void {
        $this->postInvalidData(self::ROUTE_NAME, [
            'email' => 'not-an-email'
        ]);
    }
    
    public function testSignUpFormRequiresSamePassword(): void {
        $this->postInvalidData(self::ROUTE_NAME, [
            'password' => 'secret_password',
            'password_confirmation' => 'another_password'
        ]);
    }
    
    public function testSignUpFormRejectsShortPassword(): void {
        $this->postInvalidData(self::ROUTE_NAME, [
            'email' => 'test@example.org',
            'password' => '123456',
            'password_confirmation' => '123456',
            'name' => 'user'
        ], 'password');
    }
    
    public function testSignUpFormRejectsLongName(): void {
        $this->postInvalidData(self::ROUTE_NAME, [
            'email' => 'test@example.org',
            'password' => '12345678',
            'password_confirmation' => '12345678',
            'name' => Str::random(65)
        ], 'name');
    }
    
    public function testSignUpFormRejectsInvalidCharactersInName(): void {
        $this->postInvalidData(self::ROUTE_NAME, [
            'email' => 'test@example.org',
            'password' => '12345678',
            'password_confirmation' => '12345678',
            'name' => 'ORSW%#29428'
        ], 'name');
    }
    
    public function testSignUpFormRejectsLongEmail(): void {
        $this->postInvalidData(self::ROUTE_NAME, [
            'email' => Str::random(65) . 'test@example.org',
            'password' => '12345678',
            'password_confirmation' => '12345678',
            'name' => 'username'
        ]);
    }
    
    public function testSignUpFormRejectsLongPassword(): void {
        $password = Str::random(70);        
        $this->postInvalidData(self::ROUTE_NAME, [
            'email' => 'test@example.org',
            'password' => $password,
            'password_confirmation' => $password,
            'name' => 'username'
        ], 'password');
    }
    
    public function testSignUpFormWarnsAboutExistingUsername() {
        $password = '12345678';
        $username = 'someuser';
        Player::factory()->create([
            'email' => 'test_user@example.org',
            'name' => $username,
            'password' => bcrypt($password),
        ]);
        
        $this->postInvalidData(self::ROUTE_NAME, [
            'email' => 'other_user@example.org',
            'name' => $username,
            'password' => $password,
            'password_confirmation' => $password
        ], 'name');
    }
    
    public function testSignUpFormWarnsAboutExistingEmail() {
        $password = '12345678';
        $player = Player::factory()->create([
            'email' => 'test_user@example.org',
            'name' => 'someuser',
            'password' => bcrypt($password),
        ]);
        
        $this->postInvalidData(self::ROUTE_NAME, [
            'email' => $player->email,
            'name' => 'anotheruser',
            'password' => $password,
            'password_confirmation' => $password
        ]);
    }
    
    public function testSignUpFormRegisterUser() {
        $password = '12345678';
        $email = 'test@example.org';
        $username = 'username';
        $this->postValidData(self::ROUTE_NAME, [
            'email' => $email,
            'name' => $username,
            'password' => $password,
            'password_confirmation' => $password,
            'accept' => true
        ]);
        
        $player = Player::where([
            'email' => $email,
            'name' => $username,
        ])->first();
        
        $this->assertNotNull($player, 'User was registered save in database');
    }
}
