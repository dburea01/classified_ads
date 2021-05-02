<?php

use App\Http\Controllers\ClassifiedAdController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DomainController;
use App\Http\Controllers\SiteTypeController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\CategoryGroupController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MediaController;
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

Route::get('simple-organizations', [OrganizationController::class, 'simpleOrganizations']);

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('validate-registration', [AuthController::class, 'validateRegistration']);
Route::post('lost-password', [AuthController::class, 'lostPassword']);
Route::post('reset-password', [AuthController::class, 'resetPassword']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);

    // Route::apiResource('organizations', OrganizationController::class)->whereUuid('organization');
    // Routes for the organizations
    Route::get('organizations', [OrganizationController::class, 'index'])->middleware('can:viewAny,App\Models\Organization');
    Route::get('organizations/{organization}', [OrganizationController::class, 'show'])->middleware('can:view,App\Models\Organization')->whereUuid('organization');
    Route::post('organizations', [OrganizationController::class, 'store'])->middleware('can:create,App\Models\Organization');
    Route::put('organizations/{organization}', [OrganizationController::class, 'update'])->middleware('can:update,App\Models\Organization')->whereUuid('organization');
    Route::delete('organizations/{organization}', [OrganizationController::class, 'destroy'])->middleware('can:delete,App\Models\Organization')->whereUuid('organization');
    Route::post('organizations/{organization}/logos', [OrganizationController::class, 'updateLogo'])->middleware('can:create,App\Models\Organization')->whereUuid('organization');
    Route::delete('organizations/{organization}/logos', [OrganizationController::class, 'deleteLogo'])->middleware('can:create,App\Models\Organization')->whereUuid('organization');

    // Routes for the domains of one organization
    // Route::apiResource('organizations/{organization}/domains', DomainController::class)->whereUuid(['organization', 'domain']);
    Route::get('organizations/{organization}/domains', [DomainController::class, 'index'])->middleware('can:viewAny,App\Models\Domain')->whereUuid('organization');
    Route::get('organizations/{organization}/domains/{domain:id}', [DomainController::class, 'show'])->middleware('can:view,App\Models\Domain')->whereUuid(['organization', 'domain']);
    Route::post('organizations/{organization}/domains', [DomainController::class, 'store'])->middleware('can:view,App\Models\Domain')->whereUuid('organization');
    Route::put('organizations/{organization}/domains/{domain:id}', [DomainController::class, 'update'])->middleware('can:update,App\Models\Domain')->whereUuid(['organization', 'domain']);
    Route::delete('organizations/{organization}/domains/{domain:id}', [DomainController::class, 'destroy'])->middleware('can:delete,App\Models\Domain')->whereUuid(['organization', 'domain']);

    Route::apiResource('organizations/{organization}/site-types', SiteTypeController::class)->whereUuid(['organization', 'site_type'])->scoped();
    Route::apiResource('organizations/{organization}/sites', SiteController::class)->whereUuid(['organization', 'site'])->scoped();
    Route::apiResource('organizations/{organization}/users', UserController::class)->except('store')->whereUuid(['organization', 'user']);
    Route::get('roles', [RoleController::class, 'index']);
    Route::apiResource('organizations/{organization}/category-groups', CategoryGroupController::class)->whereUuid(['organization', 'category_group']);
    Route::patch('organizations/{organization}/category-groups/sort', [CategoryGroupController::class, 'sortCategoryGroups'])->whereUuid('organization');
    Route::apiResource('organizations/{organization}/categories', CategoryController::class)->whereUuid(['organization', 'category']);
    Route::patch('organizations/{organization}/category-groups/{categoryGroup}/sortCategories', [CategoryController::class, 'sortCategories'])->whereUuid(['organization', 'category_group']);
    Route::apiResource('organizations/{organization}/classified-ads', ClassifiedAdController::class)->whereUuid(['organization', 'classified_ad']);
    Route::apiResource('organizations/{organization}/medias', MediaController::class)->whereUuid(['organization', 'media']);
});
