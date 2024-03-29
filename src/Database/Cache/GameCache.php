<?php

namespace Vesaka\Games\Database\Cache;

use Vesaka\Core\Database\Cache\ModelCache;
use Vesaka\Games\Database\Interfaces\GameInterface;
use Illuminate\Support\Facades\DB;

/**
 * Description of GameCache
 *
 * @author Vesaka
 */
class GameCache extends ModelCache implements GameInterface {

    public function purgeGuestUsers(): void {
        $this->raw();
    }
}
