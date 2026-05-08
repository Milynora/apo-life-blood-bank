<?php
namespace App\Notifications;

use App\Models\BloodRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class BloodRequestRejected extends Notification
{
    use Queueable;

    public function __construct(
        public BloodRequest $bloodRequest,
        public ?string $reason = null
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject('Blood Request Rejected — Apo Life')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Unfortunately your blood request has been rejected.')
            ->line('**Blood Type:** ' . $this->bloodRequest->bloodType->type_name)
            ->line('**Quantity:** ' . $this->bloodRequest->quantity . ' unit(s)');

        if ($this->reason) {
            $mail->line('**Reason:** ' . $this->reason);
        }

        return $mail
            ->action('View Request', url('/hospital/requests/' . $this->bloodRequest->request_id))
            ->line('Please contact us if you have questions.');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title'   => 'Blood Request Rejected',
            'message' => 'Your request for ' . $this->bloodRequest->quantity . ' unit(s) of ' . $this->bloodRequest->bloodType->type_name . ' was rejected.' . ($this->reason ? ' Reason: ' . $this->reason : ''),
            'url'     => '/hospital/requests/' . $this->bloodRequest->request_id,
            'type'    => 'blood_request',
        ];
    }
}