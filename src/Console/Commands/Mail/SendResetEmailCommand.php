<?php
namespace Vesaka\Games\Console\Commands\Mail;

use Illuminate\Console\Command;
use Vesaka\Games\Models\Player;
use Vesaka\Games\Notifications\Mail\ResetPasswordEmail;
use Illuminate\Support\Facades\Password;

/**
 * Description of SendResetEmailCommand
 *
 * @author vesak
 */
class SendResetEmailCommand extends Command {
     /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send welcome email for new users';
    
    public function handle() {
        define('CLI_GAME_HEADER_NAME', 'unblockme');
        $player = Player::find(2);
        Password::sendResetLink(['email' => $player->email], function($user, $token) {
            $player = Player::find(2);
            $player->sendPasswordResetNotification($token);
        });
    }
}
