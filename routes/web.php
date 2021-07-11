<?php

use App\Http\Controllers\StatusController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Auth::routes();

Route::get('/home', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');



Route::group(['middleware' => 'auth'], function(){
    Route::get('tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::post('tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::put('tasks/sync', [TaskController::class, 'sync'])->name('tasks.sync');
    Route::put('tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks/{task}',[TaskController::class, 'distroy'])->name('tasks.delete');
});


Route::group(['middleware' => 'auth'], function(){
    Route::get('statuses', [StatusController::class, 'index'])->name('statuses.store');
    Route::post('statuses', [StatusController::class, 'store'])->name('statuses.store');
    Route::put('statuses/{id}', [StatusController::class, 'update'])->name('statuses.update');
    Route::delete('statuses/{id}', [StatusController::class, 'distroy'])->name('statuses.delete');
});
