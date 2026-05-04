<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Verificamos si el usuario está autenticado y si es admin
        if ($request->user() && $request->user()->role === 'admin') {
            return $next($request);
        }

        // Si no es admin, bloqueamos el acceso
        return response()->json(['error' => 'Acceso restringido. Solo administradores.'], 403);
    }
}
