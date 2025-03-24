<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\Http\Middleware\CheckAbilities;
use Laravel\Sanctum\Http\Middleware\CheckForAnyAbility;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'abilities' => CheckAbilities::class,
            'ability' => CheckForAnyAbility::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => 'Recurso não encontrado',
                ], 404);
            }
        });
        $exceptions->render(function (AccessDeniedHttpException $e) {
            return response()->json([
                'message' => 'Sem autorização para acessar este recurso',
            ], 403);
        });
        $exceptions->render(function (UnauthorizedHttpException $e) {
            return response()->json([
                'message' => 'Credenciais inválidas',
            ], 401);
        });
        $exceptions->render(function (ValidationException $e, Request $request) {
            if ($request->is('api/user/login')) {
                return response()->json([
                    'message' => 'Credenciais inválidas',
                ], 401);
            }
            if ($request->is('api/*')) {
                return response()->json([
                    'errors' => $e->errors(),
                ], 422);
            }
        });
    })->create();
