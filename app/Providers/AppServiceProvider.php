<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Http\ViewComposers\MenuComposer;
use App\Http\ViewComposers\FooterComposer;

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
        // Bind ViewComposers to their respective partials
        View::composer('partials.menu', MenuComposer::class);
        View::composer('partials.footer', FooterComposer::class);
    }
}
