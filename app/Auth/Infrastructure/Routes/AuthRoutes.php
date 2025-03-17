<?php

namespace App\Auth\Infrastructure\Routes;

use App\Auth\Infrastructure\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

class AuthRoutes
{
    public static function routes()
    {
        Route::prefix('auth')->group(function () {
            Route::post('/login', [AuthController::class, 'login']);
            Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
            Route::get('/token', [AuthController::class, 'token'])->middleware('auth:sanctum');
        });
    }
}
