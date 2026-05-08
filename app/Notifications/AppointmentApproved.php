<?php
namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class AppointmentApproved extends Notification
{
    use Queueable;

    public function __construct(public Appointment $appointment) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Appointment Approved — Apo Life')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Your donation appointment has been approved.')
            ->line('**Date:** ' . $this->appointment->appointment_date->format('F d, Y h:i A'))
            ->line('**Day:** ' . $this->appointment->appointment_date->format('l'))
            ->action('View Appointment', url('/donor/appointments/' . $this->appointment->appointment_id))
            ->line('Please arrive on time. Thank you for donating blood!');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title'   => 'Appointment Approved',
            'message' => 'Your appointment on ' . $this->appointment->appointment_date->format('M d, Y h:i A') . ' has been approved.',
            'url'     => '/donor/appointments/' . $this->appointment->appointment_id,
            'type'    => 'appointment',
        ];
    }
}