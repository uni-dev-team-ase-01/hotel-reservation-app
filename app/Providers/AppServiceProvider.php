<?php

namespace App\Providers;

use App\Http\Responses\CustomFilamentLogoutResponse;
use Filament\Http\Responses\Auth\Contracts\LogoutResponse;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(LogoutResponse::class, CustomFilamentLogoutResponse::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
