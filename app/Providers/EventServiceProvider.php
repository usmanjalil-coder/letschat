<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Event::listen(Logout::class, function () {
            $user = auth()->user();
            Log::info('inside logout',[$user]);
            if ($user) {
                $user->update(['last_seen' => Carbon::now()]);
            }
        });
    }
}
