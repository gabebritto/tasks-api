<?php

namespace App\User\Infrastructure\Routes;

use Illuminate\Support\Facades\Route;

use App\User\Infrastructure\Http\Controllers\UserController;

class UserRoutes
{
    public static function routes()
    {

        Route::middleware('auth:sanctum')->group(function () {
           Route::resource('user', UserController::class);
           Route::post('user/email', [UserController::class, 'getUserByEmail']);
        });
    }
}
