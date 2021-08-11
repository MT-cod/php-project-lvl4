<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\TasksController;
use App\Http\Controllers\TaskStatusesController;
use App\Http\Controllers\LabelsController;

Route::get('/', function () {
    return view('index');
});

Auth::routes();

Route::resource('task_statuses', TaskStatusesController::class);
Route::resource('labels', LabelsController::class);
Route::resource('tasks', TasksController::class);
