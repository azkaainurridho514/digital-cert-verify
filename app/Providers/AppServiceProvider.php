<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Services\EcdsaService;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(EcdsaService::class);
    }

    public function boot(): void
    {
        Schema::defaultStringLength(191);
        $this->app->make(EcdsaService::class)->ensureKeysExist();
    }
}