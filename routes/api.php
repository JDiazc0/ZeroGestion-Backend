<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RawMaterialController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('register', [AuthController::class, 'registerUser']);
Route::post('login', [AuthController::class, 'loginUser']);

// Protected routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('logout', [AuthController::class, 'logoutUser']);

    // Protected raw material routes
    Route::group(['prefix' => 'raw-material'], function () {
        Route::post('create', [RawMaterialController::class, 'store']);
        Route::get('index', [RawMaterialController::class, 'index']);
        Route::get('show/{rawMaterial}', [RawMaterialController::class, 'show']);
        Route::put('update/{rawMaterial}', [RawMaterialController::class, 'update']);
        Route::delete('delete/{rawMaterial}', [RawMaterialController::class, 'destroy']);
    });
});
