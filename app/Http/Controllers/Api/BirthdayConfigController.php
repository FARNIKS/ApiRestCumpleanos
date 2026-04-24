<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BirthdayConfig;
use App\Http\Requests\UpdateBirthdayRequest;
use App\Http\Resources\BirthdayConfigResource;

class BirthdayConfigController extends Controller
{
    public function index()
    {
        return new BirthdayConfigResource(BirthdayConfig::first());
    }

    public function update(UpdateBirthdayRequest $request)
    {
        $config = BirthdayConfig::first();
        $config->update($request->validated());
        return response()->json(['message' => 'Actualizado', 'data' => new BirthdayConfigResource($config)]);
    }

    public function restore()
    {
        $config = BirthdayConfig::first();
        $config->update([
            'banner_url'   => 'https://www.elorbe.la/images/cumpleanos.jpg',
            'intro_text'   => "¡Feliz Cumpleaños de parte de OBGROUP!\nHoy celebramos a nuestros valiosos compañeros.",
            'main_body'    => "Les deseamos un día lleno de alegría. ¡Que lo disfruten!",
            'closing_text' => "¡Detente un momento para leer esto...!",
            'sign_off'     => "Departamento de Talento Humano"
        ]);
        return response()->json(['message' => 'Restaurado', 'data' => new BirthdayConfigResource($config)]);
    }
}
