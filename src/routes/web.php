<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\MultiAuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;

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

Route::post('/register', [MultiAuthController::class, 'userStore']);
Route::post('/login', [MultiAuthController::class, 'userLogin']);
Route::group(['middleware' => 'auth:web'], function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'userDestroy']);
    Route::get('/attendance', [UserController::class, 'attendance']);
    Route::post('/attendance/work', [UserController::class, 'workCreate']);
    Route::post('/attendance/break', [UserController::class, 'breakCreate']);
    Route::get('/attendance/list/{year?}/{month?}', [UserController::class, 'list']);
    Route::get('/attendance/detail/{id}}', [UserController::class, 'detail']);
    Route::get('/stamp_correction_request/list', [UserController::class, 'request']);
});

Route::group(['prefix' => 'admin'], function () {
    Route::group(['middleware' => 'auth:admin'], function () {
        Route::post('logout', [AuthenticatedSessionController::class, 'adminDestroy']);
        Route::get('attendances', [AdminController::class, 'index']);
        Route::get('attendances/{id}', [AdminController::class, 'detail']);
        Route::get('users', [AdminController::class, 'usersList']);
        Route::get('users/{user}/attendances', [AdminController::class, 'list']);
        Route::get('requests', [AdminController::class, 'request']);
        Route::get('requests/{id}', [AdminController::class, 'approval']);
    });
});
