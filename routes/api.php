<?php

declare(strict_types=1);

use App\Http\Controllers\TaskController;

Route::get('tasks', [TaskController::class, 'paginate']);
