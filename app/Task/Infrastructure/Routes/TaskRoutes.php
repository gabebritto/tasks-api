<?php

namespace App\Task\Infrastructure\Routes;

use App\Task\Infrastructure\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

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
