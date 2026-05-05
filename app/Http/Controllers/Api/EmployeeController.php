<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Http\Resources\EmployeeResource;

class EmployeeController extends Controller
{

    public function index()
    {
        $employees = Employee::with(['branch.country'])
            ->orderBy('Nombre', 'asc')
            ->get();

        return EmployeeResource::collection($employees);
    }

    public function show(Employee $employee): EmployeeResource
    {
        $employee->load(['branch.country']);

        return new EmployeeResource($employee);
    }
}
