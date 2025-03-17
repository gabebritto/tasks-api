<?php

namespace App\User\Infrastructure\Routes;

use Illuminate\Support\Facades\Route;

use App\User\Infrastructure\Http\Controllers\UserController;

class UserRoutes
{
    public static function routes()
    {
        Route::prefix('user')->group(function () {
            // Defina aqui as rotas que precisam de autenticação do Sanctum (Bearer + Token + Abilities (Acls))
            Route::middleware('auth:sanctum')->group(function () {
                Route::get('/list', [UserController::class,'listAll'])->middleware('ability:users-list');
                Route::get('/get-id/{id_user}', [UserController::class,'getUserById'])->middleware('ability:users-list');
                Route::post('/get-email', [UserController::class,'getUserByEmail']);
                Route::post('/save', [UserController::class,'save']);
                Route::put('/{id}', [UserController::class, 'update']);
                Route::delete('/{id}', [UserController::class, 'delete']);
            });
        });
    }
}
