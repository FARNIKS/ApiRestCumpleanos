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
    /**
     * Listar sucursales activas.
     */
    public function index()
    {
        $branches = Branch::with(['company', 'country'])
            ->where('estado', true)
            ->orderBy('code', 'asc')
            ->get();

        // Retorna la colección transformada directamente
        return BranchResource::collection($branches);
    }

    /**
     * Crear una nueva sucursal.
     */
    public function store(StoreBranchRequest $request): JsonResponse
    {
        try {
            $branch = Branch::create($request->validated());

            return response()->json([
                'status' => 'success',
                // Usamos el Resource pero dentro del array de respuesta
                'data' => new BranchResource($branch->load(['company', 'country']))
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al crear la sucursal',
                'info' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mostrar una sucursal específica.
     */
    public function show(Branch $branch)
    {
        // Retornamos el Resource directamente (Laravel lo convierte a JSON)
        return new BranchResource($branch->load(['company', 'country']));
    }

    /**
     * Actualizar sucursal.
     */
    public function update(UpdateBranchRequest $request, Branch $branch): JsonResponse
    {
        try {
            $branch->update($request->validated());

            return response()->json([
                'status'  => 'success',
                'message' => 'Sucursal actualizada correctamente',
                'data'    => new BranchResource($branch->load(['company', 'country']))
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'No se pudo actualizar la sucursal',
                'info'    => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Desactivar sucursal.
     */
    public function destroy(Branch $branch): JsonResponse
    {
        try {
            $branch->update(['estado' => false]);

            return response()->json([
                'status'  => 'success',
                'message' => 'Sucursal desactivada correctamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'info' => $e->getMessage()
            ], 500);
        }
    }
}
