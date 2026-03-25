<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/setup', function () {
    $credentials = [
        'email' => "admin@admin.com",
        'password' => "password"
    ];

    // Verificar si el usuario ya existe
    $user = User::where('email', $credentials['email'])->first();

    if (!$user) {
        // Crear nuevo usuario
        $user = User::create([
            'name' => "Admin",
            'email' => $credentials['email'],
            'password' => Hash::make($credentials['password']),
            'role' => 'admin'
        ]);
    } else {
        // Si existe, actualizar la contraseña
        $user->update([
            'password' => Hash::make($credentials['password'])
        ]);
    }

    // Autenticar al usuario
    if (Auth::attempt($credentials)) {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Generar tokens con diferentes permisos
        $adminToken = $user->createToken('admin-token', ['create', 'update', 'delete']);
        $updateToken = $user->createToken('update-token', ['create', 'update']);
        $basicToken = $user->createToken('basic-token');

        return [
            'adminToken' => $adminToken->plainTextToken,
            'updateToken' => $updateToken->plainTextToken,
            'basicToken' => $basicToken->plainTextToken,
            'user' => $user
        ];
    }

    return response()->json(['error' => 'Authentication failed'], 401);
});
