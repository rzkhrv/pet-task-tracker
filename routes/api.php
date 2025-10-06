<?php

declare(strict_types=1);

use App\Http\Controllers\TaskController;

Route::group(['prefix' => 'tasks'], function () {
    Route::get('/', [TaskController::class, 'paginate']);
    Route::post('/', [TaskController::class, 'store']);
});
