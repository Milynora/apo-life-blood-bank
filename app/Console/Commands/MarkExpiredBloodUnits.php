<?php
namespace App\Console\Commands;

use App\Enums\BloodUnitStatus;
use App\Models\BloodUnit;
use App\Models\User;
use App\Notifications\BloodUnitExpiringSoon;
use Illuminate\Console\Command;

class MarkExpiredBloodUnits extends Command
{
    protected $signature   = 'blood:expire-units';
    protected $description = 'Mark blood units past expiry date as expired and notify staff/admin of units expiring soon';

    public function handle(): void
    {
        // Mark expired units
        $expired = BloodUnit::where('status', BloodUnitStatus::Available)
            ->where('expiry_date', '<', now())
            ->update(['status' => BloodUnitStatus::Expired]);

        $this->info("Marked {$expired} unit(s) as expired.");

        // Notify staff and admin of units expiring within 7 days
        $expiringSoon = BloodUnit::with('bloodType')
            ->where('status', BloodUnitStatus::Available)
            ->whereBetween('expiry_date', [now(), now()->addDays(7)])
            ->get()
            ->groupBy('bloodType.type_name');

        if ($expiringSoon->isNotEmpty()) {
            $recipients = User::whereIn('role', ['admin', 'staff'])
                ->where('status', 'active')
                ->get();

            foreach ($expiringSoon as $bloodType => $units) {
                $daysUntilExpiry = (int) now()->diffInDays($units->first()->expiry_date);

                $recipients->each(fn($user) => $user->notify(
                    new BloodUnitExpiringSoon($bloodType, $units->count(), $daysUntilExpiry)
                ));
            }

            $this->info("Notified {$recipients->count()} staff/admin of {$expiringSoon->count()} blood type(s) expiring soon.");
        } else {
            $this->info('No blood units expiring within 7 days.');
        }
    }
}