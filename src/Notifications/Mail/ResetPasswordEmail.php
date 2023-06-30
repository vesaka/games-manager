<?php

namespace Vesaka\Games\Notifications\Mail;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

/**
 * Description of ResetPasswordEmail
 *
 * @author vesak
 */
class ResetPasswordEmail extends ResetPassword {
    /**
     * Get the reset password notification mail message for the given URL.
     *
     * @param  string  $url
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    protected function buildMailMessage($url)
    {
        return (new MailMessage)
            ->subject(__('Reset Password Notification'))
            ->line(__('You are receiving this email because we received a password reset request for your account.'))
            ->action(__('Reset Password'), $url)
            ->line(__('This password reset link will expire in :count minutes.', ['count' => config('auth.passwords.'.config('auth.defaults.passwords').'.expire')]))
            ->line(__('If you did not request a password reset, no further action is required.'));
    }

    protected function resetUrl($notifiable)
    {
        return url(route('game::player.reset-password', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
            'game' => request()->header(HEADER_GAME_NAME) ?? (defined('CLI_GAME_HEADER_NAME') ? CLI_GAME_HEADER_NAME : null)
        ], false));
    }
}
