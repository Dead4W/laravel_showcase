<?php

namespace App\Common\Framework\Providers;

use App\Car\Models\Car;
use App\Car\Resources\CarResource;
use App\Common\DeepJsonResource\DeepJsonTransformer;
use App\User\Models\User;
use App\User\Resources\UserResource;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(DeepJsonTransformer::class, function () {
            return new DeepJsonTransformer(
                castsMap: [
                    Car::class => CarResource::class,
                    User::class => UserResource::class,
                ]
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
