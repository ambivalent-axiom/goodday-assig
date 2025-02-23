<?php

namespace App\Providers;

use App\Faker\ImageProvider;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(Generator::class, function () {
            $faker = Factory::create();
            $faker->addProvider(new ImageProvider($faker));
            return $faker;
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Inertia::share([
            'auth.user.permissions' => function () {
                if (Auth::check()) {
                    return Auth::user()->getAllPermissions()->pluck('name');
                }
                return [];
            },
        ]);
    }
}
