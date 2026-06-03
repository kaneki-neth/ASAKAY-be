<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Laravel 11 automatically handles CORS based on config/cors.php
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

// Fix Laravel 12.48.1 bug: Console Kernel is incorrectly bound to HttpKernel
// See: vendor/laravel/framework/src/Illuminate/Foundation/Configuration/ApplicationBuilder.php:65-67
$app->singleton(
    \Illuminate\Contracts\Console\Kernel::class,
    \Illuminate\Foundation\Console\Kernel::class
);

return $app;
