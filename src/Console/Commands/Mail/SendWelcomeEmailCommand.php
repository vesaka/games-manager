<?php
namespace Vesaka\Games\Console\Commands\Mail;

use Illuminate\Console\Command;
use Vesaka\Games\Models\Player;
use Vesaka\Games\Notifications\Mail\WelcomeMail;

/**
 * Description of SendWelcomeEmailCommand
 *
 * @author vesak
 */
class SendWelcomeEmailCommand extends Command {
     /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:welcome';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send welcome email for new users';
    
    public function handle() {
        define('CLI_GAME_HEADER_NAME', 'unblockme');
        Player::find(2)->notify(new WelcomeMail);
    }
}
