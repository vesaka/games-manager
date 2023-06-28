<?php
namespace Vesaka\Games\Console\Commands\Player;

use Illuminate\Console\Command;
use Vesaka\Games\Models\Player;

class VerifyPlayerCommand extends Command
{
    protected $signature = 'player:verify {name}';

    protected $description = 'Verify player';

    public function handle()
    {
        $name = $this->argument('name');
        $this->info("Verify player $name");
        $player = Player::where('name', $name)->first();
        if (!$player) {
            $this->error("Player $name not found");
            return;
        }

        $player->email_verified_at = now();
        $player->save();
        $this->info("Player $name verified");
    }
}