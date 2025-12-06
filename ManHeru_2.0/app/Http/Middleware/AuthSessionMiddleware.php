<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthSessionMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!session()->has('usuario')) {
            return redirect()->route('login.form')
                ->with('error', 'Debes iniciar sesión para acceder a esta sección');
        }

        return $next($request);
    }
}