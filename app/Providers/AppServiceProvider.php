<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Helpers\Steam\Schema as SteamSchema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $globalApiKey = config('steam-api.api_key');

        config([
            'steam-auth.api_key' => $globalApiKey
        ]);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(SteamSchema::class, function ($app) {
            return new SteamSchema(config('steam-auth.api_key'));
        });
    }
}
