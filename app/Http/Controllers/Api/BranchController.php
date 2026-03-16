<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Http\Requests\StoreBranchRequest;
use App\Http\Requests\UpdateBranchRequest;
use App\Http\Resources\BranchResource;
use Illuminate\Http\JsonResponse;

class BranchController extends Controller
{
    public function index()
    {
        $branches = Branch::with('company')
            ->where('estado', true)
            ->orderBy('code', 'asc')
            ->get();

        return BranchResource::collection($branches);
    }

    public function store(StoreBranchRequest $request): JsonResponse
    {
        try {
            $branch = Branch::create($request->validated());
            return response()->json(['status' => 'success', 'data' => new BranchResource($branch->load('company'))], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'info' => $e->getMessage()], 500);
        }
    }

    public function show(Branch $branch): BranchResource
    {
        return new BranchResource($branch);
    }

    public function update(UpdateBranchRequest $request, $id): JsonResponse
    {
        try {
            // 1. Buscar la sucursal o lanzar error 404 si no existe
            $branch = Branch::findOrFail($id);

            // 2. Actualizar con los datos validados del Request
            $branch->update($request->validated());

            // 3. Cargar la empresa vinculada para la respuesta JSON
            $branch->load('company');

            return response()->json([
                'status'  => 'success',
                'message' => 'Sucursal actualizada correctamente',
                'data'    => new BranchResource($branch)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'No se pudo actualizar la sucursal',
                'info'    => $e->getMessage()
            ], 500);
        }
    }


    public function destroy(Branch $branch): JsonResponse
    {
        try {
            $branch->update(['estado' => false]);
            return response()->json([
                'status'  => 'success',
                'message' => 'Departamento desactivado correctamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'info' => $e->getMessage()], 500);
        }
    }
}
