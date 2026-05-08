<?php
namespace App\Listeners;

use App\Events\DonationRecorded;
use App\Notifications\DonationRecorded as DonationRecordedNotification;

class SendDonationNotification
{
    public function handle(DonationRecorded $event): void
    {
        $event->donation->donor->user->notify(
            new DonationRecordedNotification($event->donation)
        );
    }
}