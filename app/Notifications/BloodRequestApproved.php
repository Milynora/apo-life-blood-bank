<?php
namespace App\Notifications;

use App\Models\BloodRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class BloodRequestApproved extends Notification
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
            ->subject('Blood Request Approved — Apo Life')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Your blood request has been approved.')
            ->line('**Blood Type:** ' . $this->bloodRequest->bloodType->type_name)
            ->line('**Quantity:** ' . $this->bloodRequest->quantity . ' unit(s)')
            ->line('Blood units will be allocated shortly.')
            ->action('View Request', url('/hospital/requests/' . $this->bloodRequest->request_id))
            ->line('Thank you for partnering with Apo Life Blood Bank.');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title'   => 'Blood Request Approved',
            'message' => 'Your request for ' . $this->bloodRequest->quantity . ' unit(s) of ' . $this->bloodRequest->bloodType->type_name . ' has been approved.',
            'url'     => '/hospital/requests/' . $this->bloodRequest->request_id,
            'type'    => 'blood_request',
        ];
    }
}