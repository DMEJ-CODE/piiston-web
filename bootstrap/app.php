<?php

use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\EnsureOnboardingIsComplete;
use App\Http\Middleware\EnsureSubscriptionFeature;
use App\Http\Middleware\LocaleMiddleware;
use App\Http\Middleware\PermissionMiddleware;
use App\Http\Middleware\RoleMiddleware;
use App\Models\ApplicationErrorLog;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => RoleMiddleware::class,
            'permission' => PermissionMiddleware::class,
            'admin' => AdminMiddleware::class,
            'onboarding' => EnsureOnboardingIsComplete::class,
            'subscription.feature' => EnsureSubscriptionFeature::class,
            'locale' => LocaleMiddleware::class,
        ]);

        $middleware->appendToGroup('web', [
            LocaleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->reportable(function (Throwable $exception): void {
            if (app()->bound('db') && ! app()->runningInConsole()) {
                try {
                    ApplicationErrorLog::create([
                        'user_id' => auth()->id(), 'exception_class' => $exception::class,
                        'message' => $exception->getMessage(), 'file' => $exception->getFile(),
                        'line' => $exception->getLine(), 'method' => request()->method(),
                        'url' => request()->fullUrl(), 'ip_address' => request()->ip(),
                        'trace' => substr($exception->getTraceAsString(), 0, 20000),
                    ]);
                } catch (Throwable) {
                }
            }
        });
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();
