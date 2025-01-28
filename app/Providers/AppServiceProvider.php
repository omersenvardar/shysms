<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
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
        // Giriş yapılmışsa toplam krediyi tüm view'lere aktar
        View::composer('*', function ($view) {
            if (Auth::check() && method_exists(Auth::user(), 'totalCredits')) {
                $view->with('totalCredits', Auth::user()->totalCredits());
            } else {
                $view->with('totalCredits', 0); // Varsayılan kredi 0
            }
        });

        // MySQL key uzunluğu sorununu önlemek için varsayılan schema ayarı
        Schema::defaultStringLength(191);
    }
}
