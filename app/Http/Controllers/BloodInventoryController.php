<?php

namespace App\Http\Controllers;

use App\Models\BloodType;
use App\Models\BloodUnit;
use App\Enums\BloodUnitStatus;
use Illuminate\Http\Request;

class BloodInventoryController extends Controller
{
    public function index(Request $request)
{

BloodUnit::where('status', BloodUnitStatus::Available)
    ->where('expiry_date', '<', now())
    ->update(['status' => BloodUnitStatus::Expired]);
    
    $bloodTypes = BloodType::all();

    $inventory = BloodUnit::with('bloodType')
        ->when($request->blood_type, fn($q) => $q->where('blood_type_id', $request->blood_type))
        ->when($request->status,     fn($q) => $q->where('status', $request->status))
        ->latest('stored_date')
        ->paginate(15)
        ->withQueryString();

    $summary = BloodUnit::with('bloodType')
        ->where('status', BloodUnitStatus::Available)
        ->get()
        ->groupBy('bloodType.type_name')
        ->map(fn($units) => $units->count());

    $expiringSoon = BloodUnit::with('bloodType')
        ->where('status', BloodUnitStatus::Available)
        ->whereBetween('expiry_date', [now(), now()->addDays(7)])
        ->orderBy('expiry_date')
        ->get();

    $totalUnits     = BloodUnit::count();
    $availableUnits = BloodUnit::where('status', BloodUnitStatus::Available)->count();
    $reservedUnits  = BloodUnit::where('status', BloodUnitStatus::Reserved)->count();
    $expiredUnits   = BloodUnit::where('status', BloodUnitStatus::Expired)->count();
    $usedUnits      = BloodUnit::where('status', BloodUnitStatus::Used)->count();

return view('inventory.index', compact(
    'inventory',
    'summary',
    'expiringSoon',
    'bloodTypes',
    'totalUnits',
    'availableUnits',
    'reservedUnits',
    'usedUnits',
    'expiredUnits',
));
}
}