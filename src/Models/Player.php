<?php

namespace Vesaka\Games\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Vesaka\Games\DB\Factories\PlayerFactory;
use Vesaka\Games\Models\GameSession;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
}
