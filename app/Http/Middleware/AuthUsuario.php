<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthUsuario
{
    public function handle(Request $request, Closure $next): Response
    {
        // Permitir login y verificación
        if ($request->is('login') || $request->is('verificar')) {
            return $next($request);
        }

        // Validar sesión correcta
        if (!session()->has('usuario')) {  
            return redirect('/login');
        }

        return $next($request);
    }
}