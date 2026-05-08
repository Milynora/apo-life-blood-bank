<?php
namespace App\Services;

use App\Enums\BloodUnitStatus;
use App\Enums\RequestStatus;
use App\Models\BloodRequest;
use App\Models\BloodUnit;
use Illuminate\Support\Facades\DB;

class RequestFulfillmentService
{
    public function fulfill(BloodRequest $request): array
    {
        if ($request->status !== RequestStatus::Approved &&
            $request->status !== RequestStatus::PartiallyFulfilled) {
            return ['success' => false, 'message' => 'Request must be approved or partially fulfilled before allocating more units.'];
        }

        $alreadyAllocated = $request->bloodUnits()->count();
        $remaining        = $request->quantity - $alreadyAllocated;

        if ($remaining <= 0) {
            return ['success' => false, 'message' => 'This request has already been fully fulfilled.'];
        }

        $availableUnits = BloodUnit::where('blood_type_id', $request->blood_type_id)
            ->where('status', BloodUnitStatus::Available)
            ->where('expiry_date', '>=', now())
            ->orderBy('expiry_date') // FIFO — oldest expiry first
            ->limit($remaining)
            ->get();

        if ($availableUnits->isEmpty()) {
            return ['success' => false, 'message' => 'No available units for this blood type.'];
        }

        DB::transaction(function () use ($request, $availableUnits) {
            $newUnitIds = $availableUnits->pluck('blood_unit_id')->toArray();

            // Mark newly allocated units as reserved
            BloodUnit::whereIn('blood_unit_id', $newUnitIds)
                ->update(['status' => BloodUnitStatus::Reserved]);

            // Attach new units to request via pivot
            $request->bloodUnits()->attach($newUnitIds);

            // Check if fully fulfilled now
            $totalAllocated = $request->bloodUnits()->count();

            if ($totalAllocated >= $request->quantity) {
                // Fully fulfilled — mark ALL units linked to this request as used
                // (includes previously reserved units from earlier partial fulfillments)
                $allUnitIds = $request->bloodUnits()->pluck('blood_units.blood_unit_id')->toArray();

                BloodUnit::whereIn('blood_unit_id', $allUnitIds)
                    ->update(['status' => BloodUnitStatus::Used]);

                $request->update(['status' => RequestStatus::Fulfilled]);
            } else {
                // Still partially fulfilled
                $request->update(['status' => RequestStatus::PartiallyFulfilled]);
            }
        });

        return ['success' => true, 'units_allocated' => $availableUnits->count()];
    }
}