<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Auth\Events\Verified;
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

Route::middleware('auth:web', 'verified')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'userDestroy']);
    Route::get('/attendance', [AttendanceController::class, 'attendance']);
    Route::get('/attendance/work', [AttendanceController::class, 'workCreate']);
    Route::get('/attendance/break', [AttendanceController::class, 'breakCreate']);
    Route::get('/attendance/list/{year?}/{month?}', [AttendanceController::class, 'list']);
    Route::post('/attendance/request', [AttendanceController::class, 'requestCreate']);
});

Route::middleware('auth:web,admin', 'verified')->group(function () {
    Route::get('/stamp_correction_request/list', [AttendanceController::class, 'request']);
    Route::get('/attendance/{attendance_day_id}', [AttendanceController::class, 'detail']);
});

Route::get('/email/verify', function () {
    return view('user.auth.verify_email');
})->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $user = \App\Models\User::findOrFail($request->route('id'));

    if (! $user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();
        event(new Verified($user));
    }

    if (! Auth::check()) {
        Auth::login($user);
    }

    return redirect('/attendance');
})->middleware(['signed'])->name('verification.verify');

Route::post('/email/verification-notification', function () {
    $user = Auth::guard('web')->user();

    if ($user && is_null($user->email_verified_at)) {
        $user->sendEmailVerificationNotification();
    }

    return back();
})->middleware(['auth'])->name('verification.send');