<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Verificación normal de sesión
        if (!session()->has('id_usuario')) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'La sesión ha expirado.'], 401);
            }

            return redirect()->route('login.index');
        }

        // 2. Ejecutar la petición
        $response = $next($request);

        // 3. SOLUCIÓN: Solo aplicar cabeceras si el método existe
        if (method_exists($response, 'header')) {
            return $response->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate')
                            ->header('Pragma', 'no-cache')
                            ->header('Expires', 'Sun, 02 Jan 1990 00:00:00 GMT');
        }

        // Si es un BinaryFileResponse (Excel), retornamos la respuesta tal cual
        return $response;
    }
}
