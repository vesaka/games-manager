<?php

namespace Vesaka\Games\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Vesaka\Games\DB\Factories\PlayerFactory;
use Vesaka\Games\Notifications\Mail\WelcomeMail;
use Vesaka\Games\Notifications\Mail\ResetPasswordEmail;

/* Description of Player
*
* @author vesak
*/
class Player extends User {
    protected $table = 'users';

    protected static function newFactory(): Factory {
        return PlayerFactory::new();
    }

    public function gameSessions(): HasMany {
        return $this->hasMany(GameSession::class, 'user_id');
    }
    
    public function sendEmailVerificationNotification(): void {
        $this->notify(new WelcomeMail());
    }
    
    public function sendPasswordResetNotification($token): void {
        $this->notify(new ResetPasswordEmail($token));
    }
}
