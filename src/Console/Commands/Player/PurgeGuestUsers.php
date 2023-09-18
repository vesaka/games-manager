<?php

namespace Vesaka\Games\Console\Commands\Player;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;


class PurgeGuestUsers extends Command {

    protected $signature = 'purge:guests';

    protected $description = 'Purge guest users';

    public function handle() {
        $this->info('Purging guest users...');
        app('game')->purgeGuestUsers();
        $this->info('Purged guest users.');
    }
}