<?php

use App\Http\Controllers\API\Auth\AuthController;
use App\Http\Controllers\API\Dashboard\BuilderPricingController;
use App\Http\Controllers\API\Dashboard\ClientSurveyController;
use App\Http\Controllers\API\Dashboard\ProfileController;
use App\Http\Controllers\API\Dashboard\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])
    ->middleware(['auth:sanctum', 'signed'])
    ->name('verification.verify');

Route::name('api.')->group(function () {
    //Guest Api's
    Route::middleware('guest')->group(function () {
        Route::post('/login', [AuthController::class, 'login'])->name('login');
        Route::post('/register', [AuthController::class, 'register'])->name('register');
        Route::get('/user', function (Request $request) {
            return response()->json($request->user());
        })->name('user');

        // Reset Password Apis
        Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
        Route::get('/reset-password/{token}', [AuthController::class, 'resetPasswordToken'])->name('password.reset');
        Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
    });


    //Auth Apis
    Route::post('/refresh-token', [AuthController::class, 'refreshToken'])->name('refresh-token');

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

        //Email Verification Apis
        Route::post('/email/verification-notification', [AuthController::class, 'sendVerificationNotification'])
            ->middleware('throttle:6,1')
            ->name('verification.send');

        Route::middleware('check.activation')->group(function () {

            Route::get('/current/user', [UserController::class, 'getCurrentUser']);

            //Profile Apis
            Route::apiResource('/profile', ProfileController::class);

            Route::middleware('check.plan', 'check.activation')->group(function () {
                //Builder Pricing Apis
                Route::apiResource('/builder-pricing', BuilderPricingController::class);

                //Builder's Client Surveys
                Route::get('/client-surveys', [ClientSurveyController::class, 'index'])->name('client-surveys.index');
                Route::delete('/client-surveys/{id}', [ClientSurveyController::class, 'destroy'])->name('client-surveys.delete');
                Route::get('/client-surveys/show/{id}', [ClientSurveyController::class, 'show'])->name('client-surveys.show');
                Route::post('/client-surveys/update-status/{id}', [ClientSurveyController::class, 'updateStatus'])->name('client-surveys.status.update');
            });

            Route::middleware('check.admin')->group(function () {
                Route::get('/users', [UserController::class, 'getUsers']);
                Route::get('/builders', [UserController::class, 'getBuilders']);
                Route::get('/toggle-user-status/{id}', [UserController::class, 'toggleStatus']);
                Route::get('/all-surveys', [ClientSurveyController::class, 'getAllSurveys']);
            });
        });
    });

    //Client Survey Store
    Route::post('/client-survey/store/{id}', [ClientSurveyController::class, 'store'])->name('client-survey.store');
});
