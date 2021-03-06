<?php

use App\Http\Controllers\ClassifiedAdController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\UserController;
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

Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'login']);
Route::post('validate-registration', [UserController::class, 'validateRegistration']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [UserController::class, 'logout']);
    Route::get('classified_ads', [ClassifiedAdController::class, 'index']);
});

// Route::get('classified_ads', [ClassifiedAdController::class, 'index'])->middleware(['checkXApiKey', 'checkBearerToken']);
