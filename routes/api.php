<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;

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

Route::namespace('Admin')->group(function () {
    Route::middleware('auth:api')->group(function () {
        Route::post('/invite', [AdminController::class, 'sendInvite'])->name('send.invite');
    });

});
Route::namespace('Admin')->group(function () {
    Route::post('/login', [AdminController::class, 'login'])->name('login.user');
    Route::post('/register', [AdminController::class, 'registerUser'])->name('register.user');
    Route::post('/register/admin', [AdminController::class, 'registerAdmin'])->name('register.admin');
    Route::post('/register/otp', [AdminController::class, 'confirmOtp'])->name('confirm.user');
});
