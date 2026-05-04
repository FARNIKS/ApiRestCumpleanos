<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\StoreUserRequest;


class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $request->validate([
                'alias'    => 'required|string',
                'password' => 'required|string',
            ]);

            $alias = str_replace("'", "", strtolower($request->alias));
            $password = $request->password;

            $user = User::where('alias', $alias)->first();

            if (!$user) {
                throw new \Exception("El usuario no tiene acceso autorizado.");
            }

            if (!$user->is_active) {
                throw new \Exception("Tu cuenta está deshabilitada. Contacta al administrador.");
            }

            // Lógica LDAP
            $LDAPCONN = @ldap_connect(env('LDAP_HOST', ''));
            @ldap_set_option($LDAPCONN, LDAP_OPT_PROTOCOL_VERSION, 3);
            @ldap_set_option($LDAPCONN, LDAP_OPT_REFERRALS, 0);

            $LDAPRDN = str(env('LDAP_USER', ''))->replace('/', '\\')->toString();
            $LDAPPASS = env('LDAP_PASSWORD', '');

            if (!@ldap_bind($LDAPCONN, $LDAPRDN, $LDAPPASS)) {
                Log::error("LDAP Service Bind Failed");
                throw new \Exception("Error de conexión corporativa.");
            }

            $filter = "(samaccountname=$alias)";
            $result = @ldap_search($LDAPCONN, "DC=corp,DC=obgroup,DC=com", $filter);
            $entries = ldap_get_entries($LDAPCONN, $result);

            if ($entries['count'] == 0) {
                throw new \Exception("El alias no existe en el directorio.");
            }

            if (!@ldap_bind($LDAPCONN, $entries[0]['dn'], $password)) {
                throw new \Exception("Credenciales corporativas inválidas.");
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                "success" => "Bienvenido " . $user->name,
                "access_token" => $token,
                "token_type" => "Bearer",
                "user" => new UserResource($user)
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        } finally {
            if (isset($LDAPCONN)) {
                @ldap_unbind($LDAPCONN);
            }
        }
    }

    public function index()
    {
        return UserResource::collection(User::all());
    }

    /**
     * Registro con validación StoreUserRequest
     */
    public function register(StoreUserRequest $request)
    {
        try {
            $user = User::create([
                'name'      => $request->name,
                'email'     => $request->email,
                'alias'     => strtolower($request->alias),
                'password'  => Hash::make($request->password),
                'role'      => $request->role,
                'is_active' => true,
            ]);

            return response()->json([
                'message' => 'Usuario creado exitosamente',
                'user'    => new UserResource($user),
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al crear usuario.'], 500);
        }
    }


    public function update(UpdateUserRequest $request, User $user)
    {

        $data = $request->validated();

        if ($request->has('alias')) {
            $data['alias'] = strtolower($request->alias);
        }

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return response()->json([
            'message' => 'Usuario actualizado correctamente',
            'user'    => new UserResource($user)
        ], 200);
    }
    /**
     * Toggle con validación ToggleStatusRequest
     */
    public function toggleStatus(Request $request, User $user)
    {
        if ($request->user()->id === $user->id) {
            return response()->json(['error' => 'No puedes desactivar tu propia cuenta'], 403);
        }

        $user->update(['is_active' => !$user->is_active]);

        return response()->json([
            'message' => $user->is_active ? 'Usuario activado' : 'Usuario desactivado',
            'is_active' => $user->is_active
        ]);
    }
}
