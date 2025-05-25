<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/login';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        // Middleware Groups
        $this->app['router']->middlewareGroup('web', [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);

        $this->app['router']->middlewareGroup('api', [
            \Illuminate\Routing\Middleware\ThrottleRequests::class.':api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);

        // Route Middleware
        $this->app['router']->aliasMiddleware('auth', \App\Http\Middleware\Authenticate::class);
        $this->app['router']->aliasMiddleware('auth.basic', \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class);
        $this->app['router']->aliasMiddleware('auth.session', \Illuminate\Session\Middleware\AuthenticateSession::class);
        $this->app['router']->aliasMiddleware('cache.headers', \Illuminate\Http\Middleware\SetCacheHeaders::class);
        $this->app['router']->aliasMiddleware('can', \Illuminate\Auth\Middleware\Authorize::class);
        $this->app['router']->aliasMiddleware('guest', \App\Http\Middleware\RedirectIfAuthenticated::class);
        $this->app['router']->aliasMiddleware('password.confirm', \Illuminate\Auth\Middleware\RequirePassword::class);
        $this->app['router']->aliasMiddleware('precognitive', \Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests::class);
        $this->app['router']->aliasMiddleware('signed', \App\Http\Middleware\ValidateSignature::class);
        $this->app['router']->aliasMiddleware('throttle', \Illuminate\Routing\Middleware\ThrottleRequests::class);
        $this->app['router']->aliasMiddleware('verified', \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class);

        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
} 