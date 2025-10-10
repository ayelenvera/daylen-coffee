<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\EncryptCookies;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

date_default_timezone_set('America/Asuncion');
if (function_exists('putenv')) {
    putenv('TZ=America/Asuncion');
}

return Application::configure(basePath: dirname(__DIR__))

    ->withRouting(
        web: [
            __DIR__.'/../routes/web.php',
            __DIR__.'/../routes/test-routes.php',
            __DIR__.'/../routes/test-route.php'
        ],
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,           
            //\App\Http\Middleware\ShareShopSettings::class, // ✅ AGREGAR ESTA LÍNEA
            \App\Http\Middleware\HandleInertiaRequests::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,

        ]);

        // Registrar middleware personalizado
        $middleware->alias([
            'auth' => \App\Http\Middleware\Authenticate::class,
            'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
            'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class,
            'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
            'can' => \Illuminate\Auth\Middleware\Authorize::class,
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
            'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
            'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
            'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
            'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
            'role' => \App\Http\Middleware\CheckRole::class,
            'admin' => \App\Http\Middleware\IsAdmin::class,
            'isAdmin' => \App\Http\Middleware\IsAdmin::class,
            'shareShopSettings' => \App\Http\Middleware\ShareShopSettings::class, // ✅ AGREGAR ESTA LÍNEA
        ]);

        // Ensure cookies are properly handled
        $middleware->validateCsrfTokens(except: [
            'stripe/*',
            'http://example.com/foo/bar',
            'http://example.com/foo/*',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();