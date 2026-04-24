<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NoBirthdayConfig;
use App\Http\Requests\UpdateNoBirthdayRequest;
use App\Http\Resources\NoBirthdayConfigResource;
use Illuminate\Http\JsonResponse;

class NoBirthdayConfigController extends Controller
{
    public function index()
    {
        return new NoBirthdayConfigResource(NoBirthdayConfig::first());
    }

    public function update(UpdateNoBirthdayRequest $request): JsonResponse
    {
        $config = NoBirthdayConfig::first();
        $config->update($request->validated());

        return response()->json([
            'message' => 'Configuración de No Cumpleaños actualizada correctamente.',
            'data' => new NoBirthdayConfigResource($config)
        ]);
    }

    public function restore(): JsonResponse
    {
        $config = NoBirthdayConfig::first();
        $config->update([
            'intro_text' => "¡Buen día, equipo de OBGROUP!\n\nHoy no registramos compañeros de cumpleaños en nuestras sucursales, pero aprovechamos este espacio para compartir un mensaje de valor con todos ustedes.",
            'main_body' => "Inspiración para hoy",
            'closing_text' => "¡Les deseamos una jornada llena de productividad y éxito!",
            'sign_off' => "Departamento de Talento Humano"
        ]);
        return response()->json(['message' => 'Restaurado', 'data' => new NoBirthdayConfigResource($config)]);
    }
}
