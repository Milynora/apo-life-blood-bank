<?php
namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class BloodUnitExpiringSoon extends Notification
{
    public function __construct(
        public readonly string $bloodType,
        public readonly int $count,
        public readonly int $daysUntilExpiry
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'message'           => "{$this->count} unit(s) of {$this->bloodType} blood will expire in {$this->daysUntilExpiry} day(s).",
            'type'              => 'blood_unit_expiring',
            'blood_type'        => $this->bloodType,
            'count'             => $this->count,
            'days_until_expiry' => $this->daysUntilExpiry,
            'url'               => route('admin.inventory.index'),
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Blood Units Expiring Soon')
            ->greeting("Hello {$notifiable->name}!")
            ->line("This is an alert regarding blood inventory.")
            ->line("{$this->count} unit(s) of {$this->bloodType} blood will expire in {$this->daysUntilExpiry} day(s).")
            ->action('View Inventory', route('admin.inventory.index'))
            ->line('Please take action to allocate or flag these units.');
    }
}