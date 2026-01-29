<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('reports', function (Request $request): Limit {
            return Limit::perMinutes(5, 1)->by($request->ip());
        });

        RateLimiter::for('comments', function (Request $request): Limit {
            return Limit::perMinutes(3, 3)->by($request->ip());
        });
    }
}
