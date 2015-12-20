<?php

namespace App\Providers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use App\Helpers\Steam\APIBridge as SteamSchema;

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
        if ($this->app->environment() == 'local') {
            $this->app->register('Laracasts\Generators\GeneratorsServiceProvider');
        }

        $this->app->bind(SteamSchema::class, function ($app) {
            return new SteamSchema(config('steam-api.api_key'));
        });
    }
}
