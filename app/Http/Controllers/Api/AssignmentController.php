<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Http\Requests\StoreAssignmentRequest;
use App\Http\Requests\UpdateAssignmentRequest;
use App\Http\Resources\AssignmentResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;

class AssignmentController extends Controller
{
    public function index()
    {
        $assignments = Assignment::with(['position', 'department'])
            ->join('departments', 'assignments.department_id', '=', 'departments.id')
            ->join('positions', 'assignments.position_id', '=', 'positions.id')
            // FILTRO: Solo traer los registros donde estado sea 1 (true)
            ->where('assignments.estado', 1)
            ->orderBy('departments.name')
            ->select('assignments.*')
            ->get();

        $grouped = $assignments->groupBy(fn($asig) => $asig->department?->name ?? 'Sin Departamento');

        return response()->json(
            $grouped->map(fn($items, $dept) => [
                'label'   => $dept,
                'options' => AssignmentResource::collection($items)
            ])->values()
        );
    }

    public function store(StoreAssignmentRequest $request): JsonResponse
    {
        try {
            return DB::transaction(function () use ($request) {
                // Si ya existe uno desactivado con esa dupla, lo reactiva o crea uno nuevo
                $assignment = Assignment::updateOrCreate(
                    [
                        'department_id' => $request->department_id,
                        'position_id'   => $request->position_id,
                    ],
                    ['estado' => true]
                );

                $assignment->load(['department', 'position']);

                return response()->json([
                    'status'  => 'success',
                    'message' => 'Vínculo procesado y activado correctamente',
                    'data'    => new AssignmentResource($assignment)
                ], 201);
            });
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Error al procesar la asignación',
                'info'    => $e->getMessage()
            ], 500);
        }
    }

    public function show(Assignment $assignment): AssignmentResource
    {
        // Cargamos relaciones para que el Resource tenga los nombres de Depto y Puesto
        return new AssignmentResource($assignment->load(['position', 'department']));
    }

    public function update(UpdateAssignmentRequest $request, Assignment $assignment): JsonResponse
    {
        try {
            // Usamos una transacción para asegurar que los cambios se guarden correctamente en SQL Server
            return DB::transaction(function () use ($request, $assignment) {

                // Actualizamos los campos. 
                // Gracias a que usamos singular (department_id, position_id), coinciden con el SQL
                $assignment->update([
                    'department_id' => $request->department_id,
                    'position_id'   => $request->position_id,
                    'estado'        => $request->estado ?? $assignment->estado,
                ]);

                // Recargamos las relaciones para que la respuesta de Postman muestre los nombres actualizados
                $assignment->load(['department', 'position']);

                return response()->json([
                    'status'  => 'success',
                    'message' => 'Vínculo de catálogo actualizado correctamente',
                    'data'    => new AssignmentResource($assignment)
                ], 200);
            });
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'No se pudo actualizar la asignación',
                'info'    => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Assignment $assignment): JsonResponse
    {
        try {
            // Borrado lógico: cambiamos estado a 0
            $assignment->update(['estado' => false]);

            return response()->json([
                'status'  => 'success',
                'message' => 'Asignación desactivada del catálogo correctamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'No se pudo desactivar la asignación',
                'info'    => $e->getMessage()
            ], 500);
        }
    }
}
