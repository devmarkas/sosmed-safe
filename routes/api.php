<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\ScheduleController;

use function Psy\sh;

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

//API route for register new user
Route::post('/register', [AuthController::class, 'register']);

//API route for login user
Route::post('/login', [AuthController::class, 'login']);

// Forgot Password
Route::post('/forgot-password', [AuthController::class, 'submitForgotPasswordForm']);

//Reset Password
Route::post('/password-reset{token}', [AuthController::class, 'submitResetPasswordForm'])->name('auth.password');

//Protecting Routes
Route::group(['middleware' => ['auth:sanctum']], function () {

    // Profile
    Route::get('/profile/{id}', [ProfileController::class, 'show']);
    Route::post('/profile/{id}', [ProfileController::class, 'update']);

    //Schedule
    Route::get('/schedule', [ScheduleController::class, 'index']);
    Route::post('/schedule', [ScheduleController::class, 'store']);
    Route::post('/schedule/{id}', [ScheduleController::class, 'update']);
    Route::delete('/schedule/{id}', [ScheduleController::class, 'destroy']);


    // API route for logout user
    Route::post('/logout', [AuthController::class, 'logout']);
});
