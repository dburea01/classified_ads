<?php

use App\Http\Controllers\ClassifiedAdController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DomainController;
use App\Http\Controllers\SiteTypeController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryGroupController;
use App\Http\Controllers\CategoryController;
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

    Route::apiResource('organizations/{organization}/domains', DomainController::class)->whereUuid(['organization', 'domain']);
    Route::apiResource('organizations/{organization}/site-types', SiteTypeController::class)->whereUuid(['organization', 'site_type']);
    Route::apiResource('organizations/{organization}/sites', SiteController::class)->whereUuid(['organization', 'site']);
    Route::apiResource('organizations/{organization}/users', UserController::class)->except('store')->whereUuid(['organization', 'user']);
    Route::apiResource('organizations/{organization}/category-groups', CategoryGroupController::class)->whereUuid(['organization', 'category_group']);
    Route::apiResource('organizations/{organization}/categories', CategoryController::class)->whereUuid(['organization', 'category']);
    Route::apiResource('organizations/{organization}/classified-ads', ClassifiedAdController::class)->whereUuid(['organization', 'classified_ad']);
});
