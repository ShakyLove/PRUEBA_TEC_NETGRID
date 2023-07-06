<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::resource('user', UserController::class);
Route::resource('project', ProjectController::class);
Route::resource('task', TaskController::class);
Route::post('login', [UserController::class,'login']);
Route::get('status', [TaskController::class,'getStatus']);
Route::post('list', [ProjectController::class,'index']);
Route::post('info_project/{id}', [ProjectController::class,'show']);
