<?php

namespace App\Providers;

use App\Contracts\WeatherDataContract;
use App\Services\OpenWeatherMapCacheService;
use App\Services\OpenWeatherMapService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(WeatherDataContract::class, function () {
            $base = new OpenWeatherMapService();
            $cache = new OpenWeatherMapCacheService($base);

            return $cache;
        });
    }
}
