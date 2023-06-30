<?php

namespace Vesaka\Games\Notifications\Mail;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Carbon;
/**
 * Description of WelcomeMail
 *
 * @author vesak
 */
class WelcomeMail extends VerifyEmail {
    /**
     * Get the verify email notification mail message for the given URL.
     *
     * @param  string  $url
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    protected function buildMailMessage($url) {
        return (new MailMessage())
            ->markdown('game::mail.welcome')
            ->subject(__('Verify Email Address'))
            ->line(__('Please click the button below to verify your email address.'))
            ->action(__('Verify Email Address'), $url);
    }

    protected function verificationUrl($notifiable)
    {
        $gameKey = request()->header(HEADER_GAME_NAME) ?? (defined('CLI_GAME_HEADER_NAME') ? CLI_GAME_HEADER_NAME : null);
        return URL::temporarySignedRoute(
            'game::player.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
                'game' => $gameKey
            ]
        );
    }
}
