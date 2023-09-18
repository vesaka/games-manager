<?php

namespace Vesaka\Games\Listeners;

use Vesaka\Games\Models\{Player, GameSession};
use Illuminate\Auth\Events\{Registered, Authenticated};

class TransferGuestToPlayer
{

    public function handle(Registered|Authenticated $event): void
    {

        if (auth()->check() && auth()->user()->isGuest) {
            $guest = Player::find(auth()->id());
            $player = Player::find($event->user->id);
            app('game.session')->transfer($guest, $player);

            auth()->logout();
            $guest->delete();
        }

    }
}
