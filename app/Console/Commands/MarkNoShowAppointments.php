<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Appointment;
use App\Enums\AppointmentStatus;
use Carbon\Carbon;

class MarkNoShowAppointments extends Command
{
    protected $signature   = 'appointments:mark-no-show';
    protected $description = 'Mark approved or pending appointments as no_show if past and no screening recorded';

    public function handle(): void
    {
        $count = Appointment::whereIn('status', [
                AppointmentStatus::Approved,
                AppointmentStatus::Pending,
            ])
            ->where('appointment_date', '<', Carbon::now()->subHours(4))
            ->whereDoesntHave('screening')
            ->update(['status' => AppointmentStatus::NoShow]);

        $this->info("Marked {$count} appointment(s) as no_show.");
    }
}