<?php

namespace Vesaka\Games\Models;

use Vesaka\Games\Models\Player;
use Illuminate\Database\Eloquent\Model as AppModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Vesaka\Games\DB\Factories\GameSessionFactory;
use DB;


/**
 * Description of GameSession
 *
 * @author vesak
 */
class GameSession extends AppModel {
        
    const COMPLETED = 'completed';
    const ACTIVE = 'active';
    const FAILED = 'failed';
    
    protected $table = 'game_sessions';
    protected $fillable = ['game_id', 'user_id', 'started_at', 'ended_at', 'score', 'payload', 'info'];
    
    protected $attributes = [
        'game_id' => 1,
        'user_id' => 1,
        'score' => 0,
        'status' => self::ACTIVE
    ];
    protected $casts = [
        'started_at' => 'date',
        'ended_at' => 'date',
        'payload' => 'array',
        'info' => 'array'
    ];
    
    protected static function newFactory(): Factory {
        return GameSessionFactory::new();
    }
    
    public function player() {
        return $this->belongsTo(Player::class, 'user_id');
    }
    
    public function scopeCompleted(Builder $query): void {
        $query->where('status', self::COMPLETED);
    }
    
    public function scopeActive(Builder $query): void {
        $query->where('status', self::ACTIVE);
    }
    
    public function scopeFailed(Builder $query): void {
        $query->where('status', self::FAILED);
    }    
    
}
