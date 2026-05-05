<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\BranchController;
use App\Http\Controllers\Api\CountryController;
use App\Http\Controllers\Api\BirthdayConfigController;
use App\Http\Controllers\Api\NoBirthdayConfigController;
use App\Http\Controllers\BirthdaySettingsController;

Route::prefix('v1')->group(function () {

    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {

        Route::get('/user', function (Request $request) {
            return new \App\Http\Resources\UserResource($request->user());
        });
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/users', [AuthController::class, 'index']);

        Route::get('settings/status', [BirthdaySettingsController::class, 'index']);

        Route::prefix('settings')->group(function () {
            Route::get('/birthday', [BirthdayConfigController::class, 'index']);
            Route::get('/no-birthday', [NoBirthdayConfigController::class, 'index']);
        });

        Route::apiResources([
            'employees' => EmployeeController::class,
            'branches'  => BranchController::class,
            'countries' => CountryController::class,
        ]);

        Route::middleware('admin')->group(function () {

            Route::post('/register', [AuthController::class, 'register']);
            Route::patch('/users/{user}', [AuthController::class, 'update']);
            Route::patch('/users/status/{user}', [AuthController::class, 'toggleStatus']);

            Route::prefix('settings')->group(function () {

                Route::post('/toggle-pause', [BirthdaySettingsController::class, 'toggleStatus']);

                Route::put('/birthday', [BirthdayConfigController::class, 'update']);
                Route::post('/birthday/restore', [BirthdayConfigController::class, 'restore']);

                Route::put('/no-birthday', [NoBirthdayConfigController::class, 'update']);
                Route::post('/no-birthday/restore', [NoBirthdayConfigController::class, 'restore']);
            });
        });
    });
});
