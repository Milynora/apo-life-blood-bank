<?php
namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class AppointmentRejected extends Notification
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
            ->subject('Appointment Rejected — Apo Life')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Unfortunately your appointment request has been rejected.')
            ->line('**Requested Date:** ' . $this->appointment->appointment_date->format('F d, Y h:i A'));

        if ($this->reason) {
            $mail->line('**Reason:** ' . $this->reason);
        }

        return $mail
            ->action('Schedule Another Appointment', url('/donor/appointments/create'))
            ->line('You may submit a new appointment request for a different date.');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title'   => 'Appointment Rejected',
            'message' => 'Your appointment on ' . $this->appointment->appointment_date->format('M d, Y') . ' was rejected.' . ($this->reason ? ' Reason: ' . $this->reason : ''),
            'url'     => '/donor/appointments',
            'type'    => 'appointment',
        ];
    }
}