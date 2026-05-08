<?php
namespace App\Notifications;

use App\Models\Screening;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ScreeningRecorded extends Notification 
{
    use Queueable;

    public function __construct(public Screening $screening) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $isFit  = $this->screening->eligibility_status->value === 'fit';
        $result = $isFit ? 'ELIGIBLE to donate' : 'NOT ELIGIBLE to donate';

        $mail = (new MailMessage)
            ->subject('Screening Result — Apo Life')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Your pre-donation screening result is ready.')
            ->line('**Result:** ' . $result)
            ->line('**Date:** ' . optional($this->screening->screening_date)->format('F d, Y'));

        if ($isFit) {
            $mail->line('You are cleared to proceed with blood donation. Thank you!');
        } else {
            $mail->line('Please consult with our staff for further guidance.');
        }

        return $mail->action('View Details', url('/donor/appointments'));
    }

    public function toDatabase(object $notifiable): array
    {
        $isFit = $this->screening->eligibility_status->value === 'fit';
        return [
            'title'   => 'Screening Result: ' . ($isFit ? 'Fit' : 'Unfit'),
            'message' => 'Your screening on ' . optional($this->screening->screening_date)->format('M d, Y') . ' result: ' . ($isFit ? 'You are eligible to donate.' : 'You are not eligible at this time.'),
            'url'     => '/donor/appointments',
            'type'    => 'screening',
        ];
    }
}