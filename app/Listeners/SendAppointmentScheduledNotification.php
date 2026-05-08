<?php

namespace App\Listeners;

use App\Events\AppointmentScheduled;
use App\Notifications\AppointmentApproved;

class SendAppointmentScheduledNotification
{
    public function handle(AppointmentScheduled $event): void
    {
        $event->appointment->donor->user->notify(
            new AppointmentApproved($event->appointment)
        );
    }
}