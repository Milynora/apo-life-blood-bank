<?php
namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class AccountRejected extends Notification
{
    public function __construct(public readonly ?string $reason = null) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'message' => 'Your account registration was not approved.',
            'reason'  => $this->reason,
            'type'    => 'account_rejected',
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject('Account Registration Update')
            ->greeting("Hello {$notifiable->name}!")
            ->line('Unfortunately, your account registration was not approved at this time.');

        if ($this->reason) {
            $mail->line("Reason: {$this->reason}");
        }

        return $mail->line('If you believe this is a mistake, please contact us.');
    }
}