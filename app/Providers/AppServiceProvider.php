<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
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
        /*
        |--------------------------------------------------------------------------
        | HTTPS
        |--------------------------------------------------------------------------
        |
        | En local, le projet fonctionne avec :
        | http://localhost:8088
        |
        | En ligne avec Cloudflare, le projet fonctionne en HTTPS.
        |
        */

        if (! app()->runningInConsole()) {

            $host = request()->getHost();

            if (! in_array(
                $host,
                [
                    'localhost',
                    '127.0.0.1',
                ]
            )) {
                URL::forceScheme('https');
            }
        }
    }
}