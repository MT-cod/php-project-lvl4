<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\TasksController;
use App\Http\Controllers\TaskStatusesController;

Route::get('/', function () {
    return view('index');
});

Auth::routes();

Route::resource('task_statuses', TaskStatusesController::class);

Route::resource('tasks', TasksController::class);
