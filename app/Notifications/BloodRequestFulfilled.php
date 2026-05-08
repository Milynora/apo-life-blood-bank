<?php
namespace App\Notifications;

use App\Models\BloodRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class BloodRequestFulfilled extends Notification
{
    use Queueable;

    public function __construct(
        public BloodRequest $bloodRequest,
        public int $unitsAllocated
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $isPartial = $this->bloodRequest->status->value === 'partially_fulfilled';

        return (new MailMessage)
            ->subject(($isPartial ? 'Partial ' : '') . 'Blood Request Fulfilled — Apo Life')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line($isPartial
                ? 'Your blood request has been partially fulfilled.'
                : 'Your blood request has been fully fulfilled.')
            ->line('**Blood Type:** ' . $this->bloodRequest->bloodType->type_name)
            ->line('**Units Allocated:** ' . $this->unitsAllocated)
            ->line('**Total Requested:** ' . $this->bloodRequest->quantity)
            ->line('**Remaining:** ' . $this->bloodRequest->remaining)
            ->line('**Fulfillment Method:** ' . ucfirst($this->bloodRequest->fulfillment_type ?? 'pickup'))
            ->action('View Request', url('/hospital/requests/' . $this->bloodRequest->request_id))
            ->line($isPartial
                ? 'We will notify you when more units become available.'
                : 'Please coordinate with our staff for ' . ($this->bloodRequest->fulfillment_type === 'delivery' ? 'delivery.' : 'pickup.'));
    }

    public function toDatabase(object $notifiable): array
    {
        $isPartial = $this->bloodRequest->status->value === 'partially_fulfilled';
        return [
            'title'   => $isPartial ? 'Blood Request Partially Fulfilled' : 'Blood Request Fulfilled',
            'message' => $this->unitsAllocated . ' unit(s) of ' . $this->bloodRequest->bloodType->type_name . ' allocated. ' . ($isPartial ? $this->bloodRequest->remaining . ' unit(s) still needed.' : 'Request fully fulfilled.'),
            'url'     => '/hospital/requests/' . $this->bloodRequest->request_id,
            'type'    => 'blood_request',
        ];
    }
}