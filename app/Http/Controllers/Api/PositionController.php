<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Position;
use App\Http\Requests\StorePositionRequest;
use App\Http\Requests\UpdatePositionRequest;
use App\Http\Resources\PositionResource;
use Illuminate\Http\JsonResponse;

class PositionController extends Controller
{
    public function index()
    {
        // Recupera los cargos y cuenta sus vínculos en una sola consulta SQL
        $positions = Position::where('estado', true)
            ->orderBy('name', 'asc')
            ->get();

        return PositionResource::collection($positions);
    }

    public function store(StorePositionRequest $request): JsonResponse
    {
        try {
            $position = Position::create($request->validated());
            return response()->json([
                'status' => 'success',
                'data'   => new PositionResource($position)
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'info' => $e->getMessage()], 500);
        }
    }

    public function show(Position $position): PositionResource
    {

        return new PositionResource($position);
    }

    public function update(UpdatePositionRequest $request, $id): JsonResponse
    {
        try {
            $position = Position::findOrFail($id);
            $position->update($request->validated());
            return response()->json([
                'status' => 'success',
                'data'   => new PositionResource($position)
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'info' => $e->getMessage()], 500);
        }
    }

    public function destroy(Position $position): JsonResponse
    {
        try {
            $position->update(['estado' => false]);

            return response()->json([
                'status'  => 'success',
                'message' => 'Empleado desactivado y personal de sucursal actualizado'
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'info' => $e->getMessage()], 500);
        }
    }
}
