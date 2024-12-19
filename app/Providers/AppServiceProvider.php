<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Support\ServiceProvider;
use App\Models\ShopVisitLog;
use App\Observers\ShopVisitLogObserver;

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
    public function boot()
    {
        ShopVisitLog::observe(ShopVisitLogObserver::class);
        view()->composer('Components.layout', function ($view) {
            $categories = Category::whereNull('parentcategoryID')->get();
            $view->with('categories', $categories);
        });
    }
    
}
