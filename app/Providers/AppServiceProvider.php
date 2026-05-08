<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
{
    if (app()->environment('production')) {
        URL::forceScheme('https');
    }

    View::composer('*', function ($view) {
        if (auth()->check()) {
            $view->with(
                'unreadNotificationCount',
                auth()->user()->unreadNotifications()->count()
            );
        }
    });

    Gate::policy(\App\Models\BloodRequest::class, \App\Policies\BloodRequestPolicy::class);
    Gate::policy(\App\Models\Donation::class,     \App\Policies\DonationPolicy::class);
    Gate::policy(\App\Models\Appointment::class,  \App\Policies\AppointmentPolicy::class);

    // Only keep the blood unit creation listener
    // Notification listeners removed — notifications sent directly in controllers
    Event::listen(
        \App\Events\DonationRecorded::class,
        \App\Listeners\CreateBloodUnitsOnDonation::class,
    );
}
}