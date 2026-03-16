<?php

namespace App\Providers;

use App\Domain\Orders\Interfaces\OrderRepositoryInterface;
use App\Infrastructure\Eloquent\OrderRepository;
use Illuminate\Support\ServiceProvider;
use App\Domain\NotificationLogs\Interfaces\NotificationLogRepositoryInterface;
use App\Infrastructure\Eloquent\NotificationLogRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
        $this->app->bind(NotificationLogRepositoryInterface::class, NotificationLogRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
