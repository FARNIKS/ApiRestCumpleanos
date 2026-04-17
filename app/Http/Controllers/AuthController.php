<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash; // Necesario para el register

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $request->validate([
                'alias'    => 'required',
                'password' => 'required',
            ]);

            $alias = str_replace("'", "", strtolower($request->alias));
            $password = $request->password;

            // Conexión LDAP
            $LDAPCONN = @ldap_connect(env('LDAP_HOST', ''));
            @ldap_set_option($LDAPCONN, LDAP_OPT_PROTOCOL_VERSION, 3);
            @ldap_set_option($LDAPCONN, LDAP_OPT_REFERRALS, 0);

            $LDAPRDN = str(env('LDAP_USER', ''))->replace('/', '\\')->toString();
            $LDAPPASS = env('LDAP_PASSWORD', '');

            // Primer Bind (Credenciales de servicio)
            if (!@ldap_bind($LDAPCONN, $LDAPRDN, $LDAPPASS)) {
                throw new \Exception("Conexión con el LDAP Fallida o servidor inalcanzable.");
            }

            // Búsqueda del usuario
            $filter = "(samaccountname=$alias)";
            $result = @ldap_search($LDAPCONN, "DC=corp,DC=obgroup,DC=com", $filter);

            if (!$result) {
                throw new \Exception("Error en la búsqueda del directorio corporativo.");
            }

            $entries = ldap_get_entries($LDAPCONN, $result);
            if ($entries['count'] == 0) {
                throw new \Exception("El alias proporcionado no existe.");
            }

            $usrDn = $entries[0]['dn'];

            // Segundo Bind (Validar contraseña del usuario)
            if (!@ldap_bind($LDAPCONN, $usrDn, $password)) {
                throw new \Exception("El alias o contraseña son inválidos.");
            }

            // Verificación en DB Local
            $user = User::where('alias', $alias)->first();

            if (!$user) {
                throw new \Exception("El usuario no tiene acceso a la base de datos local.");
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                "success" => "Bienvenido " . $user->name,
                "access_token" => $token,
                "token_type" => "Bearer",
                "user" => new UserResource($user)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 400);
        } finally {
            if (isset($LDAPCONN)) {
                @ldap_unbind($LDAPCONN);
            }
        }
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'alias'    => 'required|string|unique:users|max:255', // Corregido typo y agregado unique
            'email'    => 'required|email|unique:users|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'alias'    => $request->alias,
            'email'    => $request->email,
            'password' => Hash::make($request->password), // Encriptación obligatoria
            'role'     => 'user'
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'user'         => new UserResource($user),
        ], 201);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Sesión cerrada correctamente'
        ]);
    }
}
