<?php

namespace Vesaka\Games\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;


class PurgeGuestUsers extends Command {

    protected string $signature = 'purge:guests';

    protected string $description = 'Purge guest users';

    public function handle() {
        $this->info('Purging guest users...');
        app('game')->purgeGuestUsers();
        $this->info('Purged guest users.');
    }
}