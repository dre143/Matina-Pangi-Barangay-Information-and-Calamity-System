<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Config;
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
        Paginator::useBootstrapFive();
        if ($this->app->environment('production')) {
            $scheme = parse_url(config('app.url'), PHP_URL_SCHEME) ?: 'http';
            if ($scheme === 'https') {
                URL::forceScheme('https');
                Config::set('session.domain', request()->getHost());
                Config::set('session.secure', true);
            }
        }
        
    }
}
