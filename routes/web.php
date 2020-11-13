<?php

use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\ProjectTasksController;


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

Route::group(['middleware' => 'auth'], function () {
    Route::get('/projects', [ProjectsController::class, 'index'])->name('projects.index');
    Route::get('/projects/create', [ProjectsController::class, 'create']);
    Route::get('/projects/{project}', [ProjectsController::class, 'show'])->name('projects.show');
    Route::patch('/projects/{project}', [ProjectsController::class, 'update'])->name('projects.update');

    Route::post('/projects',  [ProjectsController::class, 'store']);

    Route::post('/projects/{project}/tasks',  [ProjectTasksController::class, 'store']);
    Route::patch('/projects/{project}/tasks/{task}',  [ProjectTasksController::class, 'update']);


});



Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
