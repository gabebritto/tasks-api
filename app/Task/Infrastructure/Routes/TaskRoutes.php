<?php

namespace App\Task\Infrastructure\Routes;

use Illuminate\Support\Facades\Route;
use App\Task\Infrastructure\Http\Controllers\TaskController;

class TaskRoutes
{
    public static function routes(): void
    {
        Route::middleware('auth:sanctum')->group(function () {
            Route::resource('task', TaskController::class);
            Route::post('task/{id}/comment', [TaskController::class, 'storeComment']);
        });
    }
}
