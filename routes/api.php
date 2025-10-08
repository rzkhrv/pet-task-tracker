<?php

declare(strict_types=1);

use App\Http\Controllers\TaskController;

Route::group(['prefix' => 'tasks'], function () {
    Route::get('/', [TaskController::class, 'paginate']);
    Route::post('/', [TaskController::class, 'store']);

    Route::group(['prefix' => '{id}'], function () {
        Route::get('/', [TaskController::class, 'show']);
        Route::put('status', [TaskController::class, 'updateStatus']);
        Route::post('comments', [TaskController::class, 'storeComment']);
    });
});
