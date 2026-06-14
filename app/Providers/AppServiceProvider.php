<?php
namespace App\Providers;

use App\Repositories\BlogRepository;
use App\Repositories\Contracts\BlogRepositoryInterface;
use App\Repositories\Contracts\PartRepositoryInterface;
use App\Repositories\Contracts\QuoteRepositoryInterface;
use App\Repositories\PartRepository;
use App\Repositories\QuoteRepository;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Bind repository interfaces to implementations
        $this->app->bind(PartRepositoryInterface::class, PartRepository::class);
        $this->app->bind(QuoteRepositoryInterface::class, QuoteRepository::class);
        $this->app->bind(BlogRepositoryInterface::class, BlogRepository::class);
    }

    public function boot(): void
    {
        // Use Bootstrap pagination views
        Paginator::useBootstrap();
    }
}
