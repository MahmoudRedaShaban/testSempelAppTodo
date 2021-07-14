<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API;
use App\Http\Controllers;
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


Route::group(['middleware' => 'api'], function () {
    /* Start Auth Api User */
    Route::post('login',[API\AuthController::class ,'login'])->name('api.login');
    Route::post('register',[API\AuthController::class ,'register']);
    Route::post('logout', [API\AuthController::class ,'logout']);
    Route::post('refresh', [API\AuthController::class ,'refresh']);
    Route::post('userme', [API\AuthController::class ,'me']);
    /* End Auth Api User */

    /* Start Api Status  */
    Route::get('status', [API\StatusController::class, 'getAll']);
    Route::post('statuses', [Controllers\StatusController::class, 'store'])->name('api.statuses.store')->middleware('auth:api');
    Route::delete('statuses/{id}', [Controllers\StatusController::class, 'distroy'])->name('api.statuses.delete')->middleware('auth:api');
    /* End  Api Status  */
    
     /* Start Api Tasks  */
    Route::get('tasks', [API\TasksController::class, 'getAll']);
    Route::post('tasks', [Controllers\TaskController::class, 'store'])->name('api.tasks.store')->middleware('auth:api');;
    Route::put('tasks/sync', [Controllers\TaskController::class, 'sync'])->name('api.tasks.sync')->middleware('auth:api');;
    Route::put('tasks/{task}', [Controllers\TaskController::class, 'update'])->name('api.tasks.update')->middleware('auth:api');;
    Route::delete('/tasks/{task}',[Controllers\TaskController::class, 'distroy'])->name('api.tasks.delete')->middleware('auth:api');;
    /* End Api Tasks  */

});