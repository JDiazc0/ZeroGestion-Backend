<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\RawMaterialController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('register', [AuthController::class, 'registerUser']);
Route::post('login', [AuthController::class, 'loginUser']);

// Protected routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('logout', [AuthController::class, 'logoutUser']);

    // Protected raw material routes
    Route::group(['prefix' => 'raw-material'], function () {
        Route::post('', [RawMaterialController::class, 'store']);
        Route::get('', [RawMaterialController::class, 'index']);
        Route::get('{rawMaterial}', [RawMaterialController::class, 'show']);
        Route::put('{rawMaterial}', [RawMaterialController::class, 'update']);
        Route::delete('{rawMaterial}', [RawMaterialController::class, 'destroy']);
    });

    // Protected products routes
    Route::group(['prefix' => 'product'], function () {
        Route::post('', [ProductController::class, 'store']);
        Route::get('', [ProductController::class, 'index']);
        Route::get('{productId}', [ProductController::class, 'show']);
        Route::put('{product}', [ProductController::class, 'update']);
        Route::delete('{product}', [ProductController::class, 'destroy']);
    });

    // Protected client routes
    Route::group(['prefix' => 'client'], function () {
        Route::post('', [ClientController::class, 'store']);
        Route::get('', [ClientController::class, 'index']);
        Route::get('{clientId}', [ClientController::class, 'show']);
        Route::put('{client}', [ClientController::class, 'update']);
        Route::delete('{client}', [ClientController::class, 'destroy']);
    });
});
