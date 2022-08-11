<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('index_page');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', 'PageController@dashboard')->name('dashboard');

    Route::post('/teams/{team}/tasks', 'TaskController@createTask')->name('task_create');
    Route::patch('/tasks/{task}', 'TaskController@patchTask')->name('task_patch');
    Route::delete('/tasks/{task}', 'TaskController@deleteTask')->name('task_delete');

    Route::get('modal-demo', 'PageController@modalDemo');

});