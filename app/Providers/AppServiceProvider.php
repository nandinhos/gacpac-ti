<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Vite;
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
        Vite::prefetch(concurrency: 3);

        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        // Bridge $this->authorize('permission.name') to Spatie permissions.
        // Uses hasPermissionTo() (never $user->can(): that re-enters Gate
        // and recurses until memory exhaustion). Returns null (not false)
        // when the permission is missing/denied so policy-based checks
        // like authorize('update', $item) still resolve.
        Gate::before(function ($user, string $ability) {
            if (! method_exists($user, 'hasRole')) {
                return null;
            }

            if ($user->hasRole('admin')) {
                return true;
            }

            try {
                $r = $user ? $user->hasPermissionTo($ability) : 'nouser';
                logger()->info('GATEDBG2', ['uid' => $user?->getKey(), 'cls' => $user ? get_class($user) : null, 'a' => $ability, 'r' => $r]);
                return $r === true ? true : null;
            } catch (\Spatie\Permission\Exceptions\PermissionDoesNotExist) {
                logger()->warning('GATEDBG2-MISSING', ['a' => $ability]);
                return null;
            }
        });
    }
}
