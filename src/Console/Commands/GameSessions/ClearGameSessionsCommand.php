<?php
namespace Vesaka\Games\Console\Commands\GameSessions;

use Illuminate\Console\Command;

class ClearGameSessionsCommand extends Command {

    protected $signature = 'game-sessions:clear';

    protected $description = 'Clear game sessions';

    public function handle() {
        $this->info('Clearing game sessions...');
        $count = \DB::table('game_sessions')->delete();
        $this->info("Cleared $count game sessions");
    }
}