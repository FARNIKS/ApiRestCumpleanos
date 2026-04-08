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
        $branches = Branch::with(['country']) // Solo cargamos país
            ->where('estado', true)
            ->orderBy('code', 'asc')
            ->get();

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
                'data' => new BranchResource($branch->load(['country']))
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
        return new BranchResource($branch->load(['country']));
    }

    /**
     * Actualizar sucursal.
     */
    public function update(UpdateBranchRequest $request, Branch $branch): JsonResponse
    {
        // Si en tu modelo Branch tienes: protected $primaryKey = 'code';
        // Laravel encontrará la sucursal automáticamente por el código en la URL.
        $branch->update($request->validated());

        return response()->json([
            'status'  => 'success',
            'message' => 'Sucursal actualizada',
            'data'    => new BranchResource($branch)
        ]);
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
