<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\api\EmployeeController;
use App\Http\Controllers\api\BranchController;
use App\Http\Controllers\api\CompanyController;
use App\Http\Controllers\api\DepartmentController;
use App\Http\Controllers\api\PositionController;
use App\Http\Controllers\api\AssignmentController;

Route::prefix('v1')->group(function () {

    // --- RUTAS PÚBLICAS ---
    // Ideales para visualizar tablas y llenar selects en formularios
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);

    // Solo permitimos GET (index y show) de forma pública
    Route::get('employees', [EmployeeController::class, 'index']);
    Route::get('employees/{employee}', [EmployeeController::class, 'show']);

    Route::get('companies', [CompanyController::class, 'index']);
    Route::get('branches', [BranchController::class, 'index']);
    Route::get('departments', [DepartmentController::class, 'index']);
    Route::get('positions', [PositionController::class, 'index']);
    Route::get('assignments', [AssignmentController::class, 'index']);


    // --- RUTAS PROTEGIDAS (Requieren Token) ---
    Route::middleware('auth:sanctum')->group(function () {

        Route::get('/user', function (Request $request) {
            return new \App\Http\Resources\UserResource($request->user());
        });

        // Protegemos POST, PUT, PATCH y DELETE
        // Usamos except(['index', 'show']) porque ya los definimos arriba como públicos
        Route::apiResources([
            'employees'   => EmployeeController::class,
            'companies'   => CompanyController::class,
            'branches'    => BranchController::class,
            'departments' => DepartmentController::class,
            'positions'   => PositionController::class,
            'assignments' => AssignmentController::class,
        ], ['except' => ['index', 'show']]);

        Route::post('/logout', [AuthController::class, 'logout']);
    });
});
