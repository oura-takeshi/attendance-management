<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\AttendanceController;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/admin/login', [AuthController::class, 'adminShowLogin'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'adminLogin'])->name('admin.login.post');
Route::middleware('auth:admin')->group(function () {
    Route::post('/admin/logout', [AuthenticatedSessionController::class, 'adminDestroy']);
    Route::get('/admin/attendance/list/{year?}/{month?}/{day?}', [AttendanceController::class, 'attendance']);
    Route::get('/admin/staff/list', [AttendanceController::class, 'staff']);
    Route::get('/admin/attendance/staff/{user_id}', [AttendanceController::class, 'list']);
    Route::get('/stamp_correction_request/approval/{work_time_request_id}', [AttendanceController::class, 'approval']);
});
