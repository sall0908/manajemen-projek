<?php
use Illuminate\Support\Facades\URL;

namespace App\Providers;

use Illuminate\Support\ServiceProvider;




class AppServiceProvider extends ServiceProvider
{
   public function boot()
{
    if (config('app.env') === 'production') {
       URL::forceScheme('https');
    }
}
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
