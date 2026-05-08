<?php
namespace App\Notifications;

use App\Models\Donation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class DonationRecorded extends Notification
{
    use Queueable;

    public function __construct(public Donation $donation) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $isSuccessful = $this->donation->status->value === 'successful';

        $mail = (new MailMessage)
            ->subject('Donation Record — Apo Life')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Your blood donation has been recorded.')
            ->line('**Date:** ' . $this->donation->donation_date->format('F d, Y'))
            ->line('**Volume:** ' . $this->donation->volume . ' mL')
            ->line('**Status:** ' . ucfirst($this->donation->status->value));

        if ($isSuccessful) {
            $mail->line('Thank you for your generous donation! Your blood will help save lives.');
        } else {
            $mail->line('Unfortunately the donation was not completed successfully. Please visit us again.');
        }

        return $mail->action('View Donation History', url('/donor/donations'));
    }

    public function toDatabase(object $notifiable): array
    {
        $isSuccessful = $this->donation->status->value === 'successful';
        return [
            'title'   => 'Donation ' . ucfirst($this->donation->status->value),
            'message' => ($isSuccessful
                ? 'Your donation of ' . $this->donation->volume . ' mL on ' . $this->donation->donation_date->format('M d, Y') . ' was successful. Thank you!'
                : 'Your donation on ' . $this->donation->donation_date->format('M d, Y') . ' was not completed.'),
            'url'     => '/donor/donations',
            'type'    => 'donation',
        ];
    }
}