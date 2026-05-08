<?php
namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NewHospitalRegistered extends Notification
{
    use Queueable;

    public function __construct(public User $hospital) {}

    public function via(object $notifiable): array
{
    return ['database'];
}

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Hospital Registration — Apo Life')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('A new hospital has registered and is pending approval.')
            ->line('**Hospital:** ' . $this->hospital->name)
            ->line('**Email:** ' . $this->hospital->email)
            ->line('**Registered:** ' . $this->hospital->created_at->format('F d, Y h:i A'))
            ->action('Review Registration', url('/admin/users/' . $this->hospital->id))
            ->line('Please review and approve or reject this registration.');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title'   => 'New Hospital Registration',
            'message' => $this->hospital->name . ' has registered and is awaiting approval.',
            'url'     => '/admin/users/' . $this->hospital->id,
            'type'    => 'registration',
        ];
    }
}