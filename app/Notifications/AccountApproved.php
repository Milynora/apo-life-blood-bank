<?php
namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class AccountApproved extends Notification
{
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'message' => 'Your account has been created. You can now access the system.',
            'type'    => 'account_created',
            'url'     => route('login'),
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your Account Has Been Created')
            ->greeting("Hello {$notifiable->name}!")
            ->line('Your account has been created.')
            ->line('You can now log in and access the system.')
            ->action('Log In Now', route('login'))
            ->line('Thank you for registering with us.');
    }
}