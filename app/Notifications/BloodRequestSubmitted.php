<?php
namespace App\Notifications;

use App\Models\BloodRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class BloodRequestSubmitted extends Notification
{
    use Queueable;

    public function __construct(public BloodRequest $bloodRequest) {}

    public function via(object $notifiable): array
{
    return ['database'];
}

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Blood Request — Apo Life')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('A hospital has submitted a new blood request.')
            ->line('**Hospital:** ' . $this->bloodRequest->hospital->hospital_name)
            ->line('**Blood Type:** ' . $this->bloodRequest->bloodType->type_name)
            ->line('**Quantity:** ' . $this->bloodRequest->quantity . ' unit(s)')
            ->line('**Urgency:** ' . ucfirst($this->bloodRequest->urgency ?? 'routine'))
            ->action('Review Request', url('/staff/blood-requests/' . $this->bloodRequest->request_id))
            ->line('Please review and take appropriate action.');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title'   => 'New Blood Request',
            'message' => $this->bloodRequest->hospital->hospital_name . ' requested ' . $this->bloodRequest->quantity . ' unit(s) of ' . $this->bloodRequest->bloodType->type_name . ' (' . ucfirst($this->bloodRequest->urgency ?? 'routine') . ')',
            'url'     => '/staff/blood-requests/' . $this->bloodRequest->request_id,
            'type'    => 'blood_request',
        ];
    }
}