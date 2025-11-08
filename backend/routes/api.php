<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MenuItemController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\TestimonialController;
use App\Http\Controllers\Api\ReservationController;
use App\Http\Controllers\Api\CompanyInfoController;
use App\Http\Controllers\Api\SliderController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Public API routes for Asili Cafe
Route::prefix('v1')->group(function () {

    // Menu Items
    Route::apiResource('menu-items', MenuItemController::class);
    Route::get('menu-items/category/{category}', [MenuItemController::class, 'byCategory']);
    Route::get('menu-items/featured', [MenuItemController::class, 'featured']);

    // Services
    Route::apiResource('services', ServiceController::class);

    // Testimonials
    Route::apiResource('testimonials', TestimonialController::class);
    Route::get('testimonials/featured', [TestimonialController::class, 'featured']);

    // Reservations
    Route::apiResource('reservations', ReservationController::class);

    // Company Information
    Route::apiResource('company-info', CompanyInfoController::class);
    Route::get('company-info/key/{key}', [CompanyInfoController::class, 'getByKey']);

    // Sliders
    Route::apiResource('sliders', SliderController::class);
    Route::get('sliders/active', [SliderController::class, 'active']);

});
