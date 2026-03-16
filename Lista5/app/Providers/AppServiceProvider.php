<?php

namespace App\Providers;

use App\Domain\NotificationLogs\Interfaces\NotificationLogRepositoryInterface;
use App\Domain\Orders\Interfaces\OrderRepositoryInterface;
use App\Domain\Users\UserRepositoryInterface;
use App\Infrastructure\Eloquent\NotificationLogRepository;
use App\Infrastructure\Eloquent\OrderRepository;
use App\Infrastructure\Eloquent\UserRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
        $this->app->bind(NotificationLogRepositoryInterface::class, NotificationLogRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
