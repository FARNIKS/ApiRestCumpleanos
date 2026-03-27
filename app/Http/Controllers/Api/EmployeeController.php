<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Http\Resources\EmployeeResource;

class EmployeeController extends Controller
{

    public function index()
    {
        // Ajustamos el Eager Loading a la nueva estructura de relaciones
        // Y el ordenamiento por la columna real 'Nombre'
        $employees = Employee::with(['branch.company', 'branch.country'])
            ->orderBy('Nombre', 'asc')
            ->get();

        return EmployeeResource::collection($employees);
    }

    /**
     * Muestra el detalle de un empleado específico.
     */
    public function show(Employee $employee): EmployeeResource
    {
        // Cargamos la cadena de relaciones para el recurso
        $employee->load(['branch.company', 'branch.country']);

        return new EmployeeResource($employee);
    }
}
