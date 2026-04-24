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
     * Mostrar una sucursal específica.
     */
    public function show(Branch $branch)
    {
        // Retornamos el Resource directamente (Laravel lo convierte a JSON)
        return new BranchResource($branch->load(['country']));
    }
}
