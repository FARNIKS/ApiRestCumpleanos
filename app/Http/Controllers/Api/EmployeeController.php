<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Http\Resources\EmployeeResource;

class EmployeeController extends Controller
{

    public function index()
    {
        // Quitamos 'branch.company' porque ahora es un campo de texto en branch
        $employees = Employee::with(['branch.country'])
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
        $employee->load(['branch.country']);

        return new EmployeeResource($employee);
    }
}
