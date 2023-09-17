<?php

namespace Vesaka\Games\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model as AppModel;
use Vesaka\Games\DB\Factories\GameSessionFactory;
use Illuminate\Encryption\Encrypter;
/**
 * Description of GameSession
 *
 * @author vesak
 */
class GameSession extends AppModel {

    public const COMPLETED = 'completed';

    public const ACTIVE = 'active';

    public const FAILED = 'failed';

    protected $table = 'game_sessions';

    protected $fillable = [
        'game_id', 'user_id', 'started_at', 'ended_at',
        'score', 'payload', 'info', 'hash', 'status'
    ];

    protected $attributes = [
        'game_id' => 1,
        'user_id' => 1,
        'score' => 0,
        'status' => self::ACTIVE,
    ];

    protected $casts = [
        'started_at' => 'date',
        'ended_at' => 'date',
        'payload' => 'array',
        'info' => 'array',
    ];

    protected array|null $data = null;

    protected static function newFactory(): Factory {
        return GameSessionFactory::new();
    }

    public function game() {
        return $this->belongsTo(Game::class, 'game_id');
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

    public function getData($encrypted = false): array|null {
        if (!$this->data) {
            $json = $this->payload;

            if ($encrypted) {
                $this->data = json_decode((new Encrypter($this->hash, 'aes-256-cbc'))->decrypt($this->payload, false), true);
            } else {
                $this->data = $json;
            }
        }
        
        return $this->data;
    }

    public function getPayload(string $attribute, $encrypted = false): null | int | string |array {
        $data = $this->getData($encrypted);
        if (isset($data[$attribute])) {
            return $data[$attribute];
        }

        return null;

    }
}
