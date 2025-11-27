<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use App\Models\Asset;
use App\Models\CustodyLog;
use App\Models\InventoryRecord;
use App\Policies\AssetPolicy;
use App\Policies\CustodyLogPolicy;
use App\Policies\InventoryRecordPolicy;

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
        Vite::prefetch(concurrency: 3);

        // Registrar policies
        Gate::policy(Asset::class, AssetPolicy::class);
        Gate::policy(CustodyLog::class, CustodyLogPolicy::class);
        Gate::policy(InventoryRecord::class, InventoryRecordPolicy::class);
    }
}
