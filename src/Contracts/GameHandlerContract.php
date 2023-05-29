<?php
namespace Vesaka\Games\Contracts;

use Vesaka\Games\Models\GameSession;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
/**
 *
 * @author vesak
 */
interface GameHandlerContract {
    //put your code here
    
    public function begin(Request $request): GameSession;
    
    public function save(Request $request): GameSession;
    
    public function calculate(Request $request): int|float;
    
    public function getRanking(int $limit = 10): Collection;
    
}
