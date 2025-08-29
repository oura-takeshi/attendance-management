<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\AttendanceController;

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

Route::get('/admin/login', [AuthController::class, 'adminShowLogin']);
Route::post('/admin/login', [AuthController::class, 'adminLogin']);
Route::middleware('auth:admin')->group(function () {
    Route::post('/admin/logout', [AuthenticatedSessionController::class, 'adminDestroy']);
    Route::get('/admin/attendance/list', [AttendanceController::class, 'attendance']);
    Route::get('/admin/staff/list', [AttendanceController::class, 'staff']);
    Route::get('/admin/attendance/satff/{id}', [AttendanceController::class, 'list']);
    Route::get('/stamp_correction_request/approval/{attendance_correct_request}', [AttendanceController::class, 'approval']);
});
Route::middleware(['auth:admin', 'guard.redirect'])->group(function () {
    Route::get('/attendance/{work_time_id}', fn() => null)->name('admin.detail');
    Route::get('/stamp_correction_request/list', fn() => null)->name('admin.list');
});