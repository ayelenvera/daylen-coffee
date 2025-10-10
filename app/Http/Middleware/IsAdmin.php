<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar si el usuario está autenticado (sesión web)
        if (!auth()->check()) {
            return $this->unauthorizedResponse($request, 'Debe iniciar sesión para acceder a esta página');
        }

        // Verificar si es admin
        if (!auth()->user()->is_admin) {
            return $this->unauthorizedResponse($request, 'No tiene permisos de administrador');
        }

        return $next($request);
    }

    /**
     * Manejar respuesta no autorizada según el tipo de request
     */
    private function unauthorizedResponse(Request $request, string $message)
    {
        // Si es una request API, devolver JSON
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'message' => $message,
                'user_is_admin' => auth()->check() ? auth()->user()->is_admin : null,
            ], 403);
        }

        // Si es una request web, redirigir al home con mensaje de error
        return redirect()->route('home')->with('error', $message);
    }
}