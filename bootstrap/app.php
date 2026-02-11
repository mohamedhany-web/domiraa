<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
            'permission' => \App\Http\Middleware\CheckPermission::class,
            'permission.any' => \App\Http\Middleware\CheckAnyPermission::class,
            'log.activity' => \App\Http\Middleware\LogActivity::class,
            'sanitize' => \App\Http\Middleware\SanitizeInput::class,
            'throttle.custom' => \App\Http\Middleware\RateLimit::class,
        ]);
        
        // Add secure headers to all requests
        $middleware->append(\App\Http\Middleware\SecureHeaders::class);
        
        // Add CSRF protection (already enabled by default in Laravel)
        // Add input sanitization for POST/PUT/PATCH requests
        $middleware->validateCsrfTokens(except: [
            // Add exceptions if needed for webhooks, etc.
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Handle 419 CSRF token expired errors
        $exceptions->render(function (\Illuminate\Session\TokenMismatchException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'انتهت صلاحية الجلسة. يرجى تحديث الصفحة والمحاولة مرة أخرى.',
                    'error' => 'CSRF token mismatch'
                ], 419);
            }
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'انتهت صلاحية الجلسة. يرجى تحديث الصفحة والمحاولة مرة أخرى.');
        });
        
        // Handle 500 errors with better messages
        $exceptions->render(function (\Throwable $e, $request) {
            if (app()->environment('production')) {
                \Log::error('Unhandled exception', [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });
    })->create();
