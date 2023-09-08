<?php

namespace Vesaka\Games\Database\Interfaces;

use Vesaka\Core\Database\Interfaces\ModelInterface;

/**
 * @author Vesaka
 */
interface GameInterface extends ModelInterface {
    
    public function purgeGuestUsers(): void;
}
