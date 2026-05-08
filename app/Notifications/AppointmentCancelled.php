<?php
namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class AppointmentCancelled extends Notification
{
    use Queueable;

    public function __construct(
        public Appointment $appointment,
        public ?string $reason = null
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject('Appointment Cancelled — Apo Life')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Your appointment has been cancelled.')
            ->line('**Date:** ' . $this->appointment->appointment_date->format('F d, Y h:i A'));

        if ($this->reason) {
            $mail->line('**Reason:** ' . $this->reason);
        }

        return $mail
            ->action('Schedule New Appointment', url('/donor/appointments/create'))
            ->line('You may schedule a new appointment at your convenience.');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title'   => 'Appointment Cancelled',
            'message' => 'Your appointment on ' . $this->appointment->appointment_date->format('M d, Y') . ' was cancelled.' . ($this->reason ? ' Reason: ' . $this->reason : ''),
            'url'     => '/donor/appointments',
            'type'    => 'appointment',
        ];
    }
}