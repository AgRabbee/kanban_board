<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/tasks', [\App\Http\Controllers\TaskController::class,'allTasks'])->name('allTasks');
Route::post('/update',[\App\Http\Controllers\TaskController::class,'update'])->name('updateTask');
Route::post('/add',[\App\Http\Controllers\TaskController::class,'store'])->name('add_task');
