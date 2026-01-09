<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'sanitize' => \App\Http\Middleware\SanitizeInput::class,
            'force.https' => \App\Http\Middleware\ForceHttps::class,
            'security.headers' => \App\Http\Middleware\SecurityHeaders::class,
            'api.key' => \App\Http\Middleware\ApiKeyAuthMiddleware::class,
        ]);

        // Add security headers to all responses
        $middleware->append(\App\Http\Middleware\SecurityHeaders::class);

        // Configure API rate limiting
        $middleware->throttleApi('60,1'); // 60 requests per minute
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

