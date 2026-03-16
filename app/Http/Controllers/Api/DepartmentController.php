<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Http\Requests\StoreDepartmentRequest;
use App\Http\Requests\UpdateDepartmentRequest;
use App\Http\Resources\DepartmentResource;
use Illuminate\Http\JsonResponse;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::where('estado', true)
            ->orderBy('name', 'asc')
            ->get();

        return DepartmentResource::collection($departments);
    }

    public function store(StoreDepartmentRequest $request): JsonResponse
    {
        try {
            $department = Department::create($request->validated());
            return response()->json([
                'status' => 'success',
                'data'   => new DepartmentResource($department)
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'info' => $e->getMessage()], 500);
        }
    }
    public function show(Department $department): DepartmentResource
    {
        return new DepartmentResource($department);
    }

    public function update(UpdateDepartmentRequest $request, $id): JsonResponse
    {
        try {
            $department = Department::findOrFail($id);
            $department->update($request->validated());
            return response()->json([
                'status' => 'success',
                'data'   => new DepartmentResource($department)
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'info' => $e->getMessage()], 500);
        }
    }

    public function destroy(Department $department): JsonResponse
    {
        try {
            $department->update(['estado' => false]);
            return response()->json([
                'status'  => 'success',
                'message' => 'Departamento desactivado correctamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'info' => $e->getMessage()], 500);
        }
    }
}
