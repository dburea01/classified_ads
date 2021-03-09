<?php

use App\Http\Controllers\ClassifiedAdController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\CheckBearerToken;
use App\Http\Middleware\CheckXApiKey;
use Illuminate\Support\Facades\Route;

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

Route::get('organizations', [OrganizationController::class, 'index']);

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('validate-registration', [AuthController::class, 'validateRegistration']);
Route::post('lost-password', [AuthController::class, 'lostPassword']);
Route::post('reset-password', [AuthController::class, 'resetPassword']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);

    Route::apiResource('organizations', OrganizationController::class)->except('index')->whereUuid('organization');
    Route::post('organizations/{organization}/logos', [OrganizationController::class, 'updateLogo'])->whereUuid('organization');
    Route::delete('organizations/{organization}/logos', [OrganizationController::class, 'deleteLogo'])->whereUuid('organization');

    Route::get('classified_ads', [ClassifiedAdController::class, 'index']);
});

// Route::get('classified_ads', [ClassifiedAdController::class, 'index'])->middleware(['checkXApiKey', 'checkBearerToken']);
