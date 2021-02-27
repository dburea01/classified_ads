<?php

use App\Http\Controllers\ClassifiedAdController;
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

Route::middleware(['checkXApiKey', 'checkBearerToken'])->group(function () {
    Route::get('classified_ads', [ClassifiedAdController::class, 'index']);
});
