<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\BranchController;
use App\Http\Controllers\Api\CountryController;
// Importamos los nuevos controladores
use App\Http\Controllers\Api\BirthdayConfigController;
use App\Http\Controllers\Api\NoBirthdayConfigController;

Route::prefix('v1')->group(function () {

    // --- RUTAS PÚBLICAS ---
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);

    // --- RUTAS PROTEGIDAS (Requieren Token Sanctum) ---
    Route::middleware('auth:sanctum')->group(function () {

        // Información del usuario autenticado
        Route::get('/user', function (Request $request) {
            return new \App\Http\Resources\UserResource($request->user());
        });

        // Configuración de Correos de Cumpleaños
        Route::prefix('settings')->group(function () {
            // Rutas para Cumpleaños (Birthday)
            Route::get('/birthday', [BirthdayConfigController::class, 'index']);
            Route::put('/birthday', [BirthdayConfigController::class, 'update']);
            Route::post('/birthday/restore', [BirthdayConfigController::class, 'restore']);

            // Rutas para No Cumpleaños (No Birthday)
            Route::get('/no-birthday', [NoBirthdayConfigController::class, 'index']);
            Route::put('/no-birthday', [NoBirthdayConfigController::class, 'update']);
            Route::post('/no-birthday/restore', [NoBirthdayConfigController::class, 'restore']);
        });

        Route::apiResources([
            'employees' => EmployeeController::class,
            'branches'  => BranchController::class,
            'countries' => CountryController::class,
        ]);

        Route::post('/logout', [AuthController::class, 'logout']);
    });
});
