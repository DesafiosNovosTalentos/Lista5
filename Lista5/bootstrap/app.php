<?php

use App\Exceptions\EntityNotFoundException;
use App\Exceptions\RepositoryException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {

        $exceptions->render(function (InvalidArgumentException $e) {
            if (request()->is('api/*')) {
                return response()->json(['message' => $e->getMessage()], 422);
            }
        });

        $exceptions->render(function (RepositoryException $e) {
            if (request()->is('api/*')) {
                return response()->json([
                    'error' => 'Erro interno no servidor',
                    'message' => $e->getMessage(),
                ], 500);
            }
        });

        $exceptions->render(function (EntityNotFoundException $e) {
            if (request()->is('api/*')) {
                return response()->json(['message' => $e->getMessage()], 404);
            }
        });
    })->create();
