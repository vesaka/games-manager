<?php

namespace Vesaka\Games\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Description of EnsureGameExists
 *
 * @author vesak
 */
class EnsureGameExists {
    public function handle(Request $request, Closure $next) {
        $gameKey = config('games.identifier');
        $gameStrategy = config('games.catalogue.'.$request->get($gameKey));
        if ($gameStrategy) {
            $next($request);

            return;
        }

        throw new \Exception('Game not found in our Catalogue', 403);
    }
}
