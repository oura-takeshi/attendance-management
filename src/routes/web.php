<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\User\AuthController;
use App\Http\Controllers\User\AttendanceController;

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

Route::post('/register', [AuthController::class, 'userStore']);
Route::post('/login', [AuthController::class, 'userLogin']);
Route::middleware('auth:web')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'userDestroy']);
    Route::get('/attendance', [AttendanceController::class, 'attendance']);
    Route::get('/attendance/work', [AttendanceController::class, 'workCreate']);
    Route::get('/attendance/break', [AttendanceController::class, 'breakCreate']);
    Route::get('/attendance/list/{year?}/{month?}', [AttendanceController::class, 'list']);
});
Route::middleware(['auth:web', 'guard.redirect'])->group(function () {
    Route::get('/attendance/{work_time_id}', fn() => null)->name('user.detail');
    Route::get('/stamp_correction_request/list', fn() => null)->name('user.list');
});