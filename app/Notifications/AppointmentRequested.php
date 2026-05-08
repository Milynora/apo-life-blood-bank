<?php
namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class AppointmentRequested extends Notification
{
    use Queueable;

    public function __construct(public Appointment $appointment) {}

    public function via(object $notifiable): array
{
    return ['database']; // Email would hit rate limit with multiple staff
}

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Appointment Request — Apo Life')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('A donor has requested a donation appointment.')
            ->line('**Donor:** ' . $this->appointment->donor->name)
            ->line('**Date:** ' . $this->appointment->appointment_date->format('F d, Y h:i A'))
            ->action('View Appointment', url('/staff/appointments/' . $this->appointment->appointment_id))
            ->line('Please review and approve or reject this appointment.');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title'   => 'New Appointment Request',
            'message' => $this->appointment->donor->name . ' requested an appointment on ' . $this->appointment->appointment_date->format('M d, Y h:i A'),
            'url'     => '/staff/appointments/' . $this->appointment->appointment_id,
            'type'    => 'appointment',
        ];
    }
}