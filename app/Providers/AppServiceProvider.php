<?php

namespace App\Providers;

use App\Services\BenfordsLawService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(BenfordsLawService::class, function () {
            return new BenfordsLawService(
                config('benfordslaw.distribution'),
                config('benfordslaw.variance'),
                config('benfordslaw.minLength')
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
