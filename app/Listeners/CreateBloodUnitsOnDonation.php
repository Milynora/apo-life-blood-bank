<?php
namespace App\Listeners;

use App\Events\DonationRecorded;
use App\Services\BloodInventoryService;

class CreateBloodUnitsOnDonation
{
    public function __construct(protected BloodInventoryService $inventoryService) {}

    public function handle(DonationRecorded $event): void
    {
        $this->inventoryService->createUnitsFromDonation($event->donation);
    }
}