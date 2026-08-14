<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            \Illuminate\Support\Facades\Route::middleware(['web', 'auth', \App\Http\Middleware\AdminAuthenticate::class])
                ->prefix('adminadspnl')
                ->name('admin.')
                ->group(base_path('routes/admin.php'));

            \Illuminate\Support\Facades\Route::middleware(['web'])
                ->prefix('adminadspnl')
                ->name('admin.')
                ->group(base_path('routes/admin_auth.php'));

            \Illuminate\Support\Facades\Route::middleware(['web', 'auth', \App\Http\Middleware\StaffAuthenticate::class])
                ->prefix('staffadspnl')
                ->name('staff.')
                ->group(base_path('routes/staff.php'));

            \Illuminate\Support\Facades\Route::middleware(['web'])
                ->prefix('staffadspnl')
                ->name('staff.')
                ->group(base_path('routes/staff_auth.php'));

            \Illuminate\Support\Facades\Route::middleware(['web'])
                ->prefix('client')
                ->name('client.')
                ->group(base_path('routes/client.php'));

            \Illuminate\Support\Facades\Route::middleware(['api'])
                ->prefix('api')
                ->name('api.')
                ->group(base_path('routes/api.php'));

            \Illuminate\Support\Facades\Route::middleware(['api'])
                ->prefix('webhook')
                ->name('webhook.')
                ->group(base_path('routes/webhook.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->append(\App\Http\Middleware\NoIndexMiddleware::class);
        $middleware->redirectTo(
            guests: function (Request $request) {
                if ($request->is('adminadspnl*')) {
                    return route('admin.login');
                }
                if ($request->is('staffadspnl*')) {
                    return route('staff.login');
                }
                return route('login');
            }
        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
    })->create();
