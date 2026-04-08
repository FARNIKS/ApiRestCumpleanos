<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\BranchController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\CountryController;

Route::prefix('v1')->group(function () {

    // --- RUTAS PÚBLICAS ---
    // Ideales para el Login y para que el Frontend cargue Selects/Tablas iniciales
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);

    // --- RUTAS PROTEGIDAS (Requieren Token Sanctum) ---
    Route::middleware('auth:sanctum')->group(function () {

        // Información del usuario autenticado
        Route::get('/user', function (Request $request) {
            return new \App\Http\Resources\UserResource($request->user());
        });

        Route::apiResources([
            'employees' => EmployeeController::class,
            'branches'  => BranchController::class,
            'countries' => CountryController::class,
        ]);

        Route::post('/logout', [AuthController::class, 'logout']);
    });
});
