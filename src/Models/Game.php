<?php
namespace Vesaka\Games\Models;

use Vesaka\Core\Models\Model;
use Vesaka\Games\DB\Factories\GameFactory;

/**
 * Description of Game
 *
 * @author vesak
 */
class Game extends Model {
    protected $attributes = [
        'type' => 'game'
    ]; 
    
    protected static function newFactory(): GameFactory {
        return GameFactory::new();
    }
}
