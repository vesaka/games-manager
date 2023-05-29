<?php
namespace Vesaka\Games\Tests\Traits;
use Vesaka\Games\Database\Repositories\GameSessionRepository;
use Vesaka\Games\Database\Interfaces\GameSessionInterface;
use Vesaka\Games\Models\GameSession;
/**
 * Description of BindsGameSessionRepository
 *
 * @author vesak
 */
trait BindsGameSessionRepository {
    
    public function bindGameSessionAlias() {
        app()->bind(GameSessionInterface::class, function() {
            return new GameSessionRepository(new GameSession);
        });
        
        app()->alias(GameSessionInterface::class, 'game.session');
    }
}
