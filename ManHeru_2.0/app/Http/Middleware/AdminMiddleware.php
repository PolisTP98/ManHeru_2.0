<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Verificar si hay usuario en sesión
        if (!session()->has('usuario')) {
            return redirect()->route('login.form')
                ->with('error', 'Debes iniciar sesión para acceder a esta sección');
        }

        // Verificar si el usuario tiene rol de administrador (ID_Rol = 1)
        if (session('usuario')->ID_Rol != 1) {
            return redirect()->route('inicio')
                ->with('error', 'No tienes permisos de administrador para acceder a esta sección');
        }

        return $next($request);
    }
}