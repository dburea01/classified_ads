<?php

use App\Http\Controllers\ClassifiedAdController;
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

Route::middleware([CheckXApiKey::class, CheckBearerToken::class])->group(function () {
    Route::get('classified_ads', [ClassifiedAdController::class, 'index']);
});

// Route::get('classified_ads', [ClassifiedAdController::class, 'index'])->middleware(['checkXApiKey', 'checkBearerToken']);
