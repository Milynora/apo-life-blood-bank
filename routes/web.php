<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\BloodInventoryController;
use App\Http\Controllers\Admin\DashboardController          as AdminDash;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\BloodRequestController       as AdminRequestCtrl;
use App\Http\Controllers\Admin\ReportsController;
use App\Http\Controllers\Staff\DashboardController          as StaffDash;
use App\Http\Controllers\Staff\DonorController;
use App\Http\Controllers\Staff\DonationController;
use App\Http\Controllers\Staff\ScreeningController;
use App\Http\Controllers\Staff\AppointmentController        as StaffApptCtrl;
use App\Http\Controllers\Donor\DashboardController          as DonorDash;
use App\Http\Controllers\Donor\ProfileController            as DonorProfile;
use App\Http\Controllers\Donor\AppointmentController        as DonorApptCtrl;
use App\Http\Controllers\Donor\DonationHistoryController;
use App\Http\Controllers\Hospital\DashboardController       as HospitalDash;
use App\Http\Controllers\Hospital\BloodRequestController    as HospitalRequestCtrl;
use App\Http\Controllers\Hospital\ProfileController         as HospitalProfile;

Route::get('/', fn() => view('welcome'))->name('home');

require __DIR__.'/auth.php';

Route::middleware(['auth', 'approved'])->group(function () {

    // ── Notifications ─────────────────────────────────────────────────────────
    Route::get('/notifications',               [NotificationController::class, 'index'])->name('notifications.index');
    Route::patch('/notifications/read-all',    [NotificationController::class, 'markAllRead'])->name('notifications.readAll');
    Route::delete('/notifications/read/clear', [NotificationController::class, 'destroyAllRead'])->name('notifications.destroyAllRead');
    Route::patch('/notifications/{id}/read',   [NotificationController::class, 'markRead'])->name('notifications.read');

    // ── Admin ─────────────────────────────────────────────────────────────────
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {

        Route::get('/', [AdminDash::class, 'index'])->name('dashboard');

        // User management — literal routes BEFORE wildcards
        Route::get('/users',                 [UserManagementController::class, 'index'])->name('users.index');
        Route::get('/users/create-staff',    [UserManagementController::class, 'createStaff'])->name('users.create-staff');
        Route::post('/users/create-staff',   [UserManagementController::class, 'storeStaff'])->name('users.store-staff');
        Route::get('/users/create-donor',    [UserManagementController::class, 'createDonor'])->name('users.create-donor');
        Route::post('/users/create-donor',   [UserManagementController::class, 'storeDonor'])->name('users.store-donor');
        Route::get('/users/create-hospital', [UserManagementController::class, 'createHospital'])->name('users.create-hospital');
        Route::post('/users/create-hospital',[UserManagementController::class, 'storeHospital'])->name('users.store-hospital');

        // Wildcard user routes AFTER literal routes
        Route::get('/users/{user}',              [UserManagementController::class, 'show'])->name('users.show')->withTrashed();
        Route::patch('/users/{user}',            [UserManagementController::class, 'update'])->name('users.update');
        Route::patch('/users/{user}/approve',    [UserManagementController::class, 'approve'])->name('users.approve');
        Route::patch('/users/{user}/reject',     [UserManagementController::class, 'reject'])->name('users.reject');
        Route::patch('/users/{user}/suspend',    [UserManagementController::class, 'suspend'])->name('users.suspend');
        Route::patch('/users/{user}/reactivate', [UserManagementController::class, 'reactivate'])->name('users.reactivate');
        Route::patch('/users/{id}/restore',      [UserManagementController::class, 'restore'])->name('users.restore')->withTrashed();
        Route::delete('/users/{user}',           [UserManagementController::class, 'destroy'])->name('users.destroy');

        // Blood requests
        Route::get('/blood-requests',                          [AdminRequestCtrl::class, 'index'])->name('blood-requests.index');
        Route::get('/blood-requests/{bloodRequest}',           [AdminRequestCtrl::class, 'show'])->name('blood-requests.show');
        Route::patch('/blood-requests/{bloodRequest}/approve', [AdminRequestCtrl::class, 'approve'])->name('blood-requests.approve');
        Route::patch('/blood-requests/{bloodRequest}/reject',  [AdminRequestCtrl::class, 'reject'])->name('blood-requests.reject');
        Route::patch('/blood-requests/{bloodRequest}/fulfill', [AdminRequestCtrl::class, 'fulfill'])->name('blood-requests.fulfill');

        Route::get('/reports',   [ReportsController::class, 'index'])->name('reports.index');
        Route::get('/inventory', [BloodInventoryController::class, 'index'])->name('inventory.index');

        // Admin access to staff modules
        Route::resource('donors', \App\Http\Controllers\Staff\DonorController::class)->only(['index','create','store','show','update']);
        Route::resource('donations',    \App\Http\Controllers\Staff\DonationController::class)->only(['index','create','store','show']);
        Route::patch('donations/{donation}', [\App\Http\Controllers\Staff\DonationController::class, 'update'])->name('donations.update');
        Route::resource('screenings',   \App\Http\Controllers\Staff\ScreeningController::class)->only(['index','create','store','show']);
        Route::patch('screenings/{screening}', [\App\Http\Controllers\Staff\ScreeningController::class, 'update'])->name('screenings.update');
        Route::resource('appointments', \App\Http\Controllers\Staff\AppointmentController::class)->only(['index','create','store','show','update']);
    });

    // ── Staff ─────────────────────────────────────────────────────────────────
Route::middleware('role:staff,admin')->prefix('staff')->name('staff.')->group(function () {

    Route::get('/', [StaffDash::class, 'index'])->name('dashboard');

    // Donors & Hospitals unified index
    Route::get('/donors',              [DonorController::class, 'index'])->name('donors.index');
    Route::get('/donors/create',       [DonorController::class, 'create'])->name('donors.create');
    Route::post('/donors',             [DonorController::class, 'store'])->name('donors.store');
    Route::get('/donors/{donor}',      [DonorController::class, 'show'])->name('donors.show');
    Route::patch('/donors/{donor}',    [DonorController::class, 'update'])->name('donors.update');
    Route::delete('/donors/{donor}',   [DonorController::class, 'destroy'])->name('donors.destroy');

    // Hospital routes for staff
    Route::get('/hospitals/create',        [DonorController::class, 'createHospital'])->name('hospitals.create');
    Route::post('/hospitals',              [DonorController::class, 'storeHospital'])->name('hospitals.store');
    Route::get('/hospitals/{hospital}',    [DonorController::class, 'showHospital'])->name('hospitals.show');
    Route::patch('/hospitals/{hospital}',  [DonorController::class, 'updateHospital'])->name('hospitals.update');

    Route::resource('donations',    DonationController::class)->only(['index','create','store','show']);
    Route::patch('donations/{donation}', [DonationController::class, 'update'])->name('donations.update');
    Route::resource('screenings',   ScreeningController::class)->only(['index','create','store','show']);
    Route::patch('screenings/{screening}', [ScreeningController::class, 'update'])->name('screenings.update');
    Route::resource('appointments', StaffApptCtrl::class)->only(['index','create','store','show','update']);

    // Blood requests
    Route::get('/blood-requests',                          [AdminRequestCtrl::class, 'index'])->name('blood-requests.index');
    Route::get('/blood-requests/{bloodRequest}',           [AdminRequestCtrl::class, 'show'])->name('blood-requests.show');
    Route::patch('/blood-requests/{bloodRequest}/approve', [AdminRequestCtrl::class, 'approve'])->name('blood-requests.approve');
    Route::patch('/blood-requests/{bloodRequest}/reject',  [AdminRequestCtrl::class, 'reject'])->name('blood-requests.reject');
    Route::patch('/blood-requests/{bloodRequest}/fulfill', [AdminRequestCtrl::class, 'fulfill'])->name('blood-requests.fulfill');

    Route::get('/inventory', [BloodInventoryController::class, 'index'])->name('inventory.index');
    Route::get('/reports',   [ReportsController::class, 'index'])->name('reports.index');
});

    // ── Donor ─────────────────────────────────────────────────────────────────
    Route::middleware('role:donor')->prefix('donor')->name('donor.')->group(function () {

        Route::get('/', [DonorDash::class, 'index'])->name('dashboard');

        Route::get('/profile',           [DonorProfile::class, 'edit'])->name('profile.edit');
        Route::patch('/profile',         [DonorProfile::class, 'updateProfile'])->name('profile.update');
        Route::patch('/profile/email',   [DonorProfile::class, 'updateEmail'])->name('profile.email');
        Route::patch('/profile/password',[DonorProfile::class, 'updatePassword'])->name('profile.password');
        Route::post('/profile/avatar',   [DonorProfile::class, 'updateAvatar'])->name('profile.avatar');
        Route::delete('/profile/avatar', [DonorProfile::class, 'removeAvatar'])->name('profile.avatar.remove');

        Route::resource('appointments', DonorApptCtrl::class)->only(['index','create','store','show','destroy']);

        Route::get('/donations', [DonationHistoryController::class, 'index'])->name('donations.index');
    });

    // ── Hospital ──────────────────────────────────────────────────────────────
Route::middleware('role:hospital')->prefix('hospital')->name('hospital.')->group(function () {

    Route::get('/', [HospitalDash::class, 'index'])->name('dashboard');

    Route::get('/profile',           [HospitalProfile::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',         [HospitalProfile::class, 'update'])->name('profile.update');
    Route::patch('/profile/email',   [HospitalProfile::class, 'updateEmail'])->name('profile.email');
    Route::patch('/profile/password',[HospitalProfile::class, 'updatePassword'])->name('profile.password');

    // Requests — explicit routes so cancel/edit don't clash with resource wildcards
    Route::get('/requests',                      [HospitalRequestCtrl::class, 'index'])->name('requests.index');
    Route::get('/requests/create',               [HospitalRequestCtrl::class, 'create'])->name('requests.create');
    Route::post('/requests',                     [HospitalRequestCtrl::class, 'store'])->name('requests.store');
    Route::get('/requests/{id}',                 [HospitalRequestCtrl::class, 'show'])->name('requests.show');
    Route::get('/requests/{id}/edit',            [HospitalRequestCtrl::class, 'edit'])->name('requests.edit');
    Route::put('/requests/{id}',                 [HospitalRequestCtrl::class, 'update'])->name('requests.update');
    Route::patch('/requests/{id}/cancel',        [HospitalRequestCtrl::class, 'cancel'])->name('requests.cancel');

    Route::get('/inventory', [BloodInventoryController::class, 'index'])->name('inventory.index');
});
});