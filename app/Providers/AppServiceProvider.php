<?php

namespace App\Providers;

use App\Services\RecommendationService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Register recommendation service as singleton
        // so it is only instantiated once per request
        $this->app->singleton(RecommendationService::class, function () {
            return new RecommendationService();
        });
    }

    public function boot(): void
    {
        // Use Bootstrap-compatible pagination (or Tailwind — pick one)
        // We use simple blade pagination to match our custom CSS
        Paginator::defaultView('vendor.pagination.simple-bootstrap-5');
        Paginator::defaultSimpleView('vendor.pagination.simple-bootstrap-5');
    }
}