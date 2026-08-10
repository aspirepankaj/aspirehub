<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as BaseResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;

class ResetPasswordNotification extends BaseResetPassword
{
    /**
     * Build the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        $url = $this->resetUrl($notifiable);

        return (new MailMessage)
            ->subject(Lang::get('Reset Your Aspire Hub Password'))
            ->view('emails.reset-password', [
                'url'        => $url,
                'count'      => config('auth.passwords.' . config('auth.defaults.passwords') . '.expire', 60),
                'notifiable' => $notifiable,
            ]);
    }
}
