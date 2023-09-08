<?php

namespace Vesaka\Games\Database\Repositories;

use Vesaka\Core\Database\Repositories\ModelRepository;
use Vesaka\Games\Database\Interfaces\GameInterface;
use Illuminate\Support\Facades\DB;

/**
 * Description of GameRepository
 *
 * @author Vesaka
 */
class GameRepository extends ModelRepository implements GameInterface {

    public function purgeGuestUsers(): void {
        DB::beginTransaction();
       
        $userIds = app('user')->select('id')
        ->where('email', 'like', '%@guest.com')
        ->get()
        ->pluck('id');

        app('game.session')->whereIn('user_id', $userIds)->delete();
        app('user')->whereIn('id', $userIds)->delete();

        DB::commit();
    }
}
