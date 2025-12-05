<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;  // Cambiado de Usuario a User
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Mostrar formulario de login
     */
    public function showLogin()
    {
        return view('login');
    }

    /**
     * Procesar login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // Buscar usuario por email (Gmail en tu BD)
        $user = User::where('Gmail', $request->email)->first();

        if ($user) {
            // Verificar contraseña
            if (Hash::check($request->password, $user->Contrasena)) {
                // Verificar si el usuario está activo
                if ($user->Estatus == 1) {
                    // Guardar el usuario COMPLETO en sesión
                    session(['usuario' => $user]);
                    
                    return redirect()->route('inicio')
                        ->with('success', '¡Bienvenido ' . $user->Nombre . '!');
                } else {
                    return back()->withErrors([
                        'email' => 'Tu cuenta está desactivada. Contacta al administrador.',
                    ]);
                }
            }
        }

        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no son válidas.',
        ]);
    }

    /**
     * Mostrar formulario de registro
     */
    public function showRegister()
    {
        return view('registro');
    }

    /**
     * Procesar registro
     */
    public function register(Request $request)
    {
        $request->validate([
            'Nombre' => 'required|string|max:100',
            'Gmail' => 'required|email|unique:usuarios,Gmail',
            'Contrasena' => 'required|min:6|confirmed',
            'Contrasena_confirmation' => 'required',
            'Telefono' => 'required|string|max:20',
        ]);

        try {
            // Crear nuevo usuario usando el modelo User
            $user = User::create([
                'Nombre' => $request->Nombre,
                'Gmail' => $request->Gmail,
                'Contrasena' => Hash::make($request->Contrasena),
                'Telefono' => $request->Telefono,
                'ID_Rol' => 2, // Rol de usuario normal por defecto
                'Estatus' => 1, // Activo
            ]);

            // Guardar usuario en sesión manual
            session(['usuario' => $user]);

            return redirect()->route('inicio')
                ->with('success', '¡Cuenta creada exitosamente! Bienvenido ' . $user->Nombre);

        } catch (\Exception $e) {
            return back()->withErrors([
                'error' => 'Error al crear la cuenta: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * Cerrar sesión
     */
    public function logout(Request $request)
    {
        // Eliminar usuario de la sesión
        $request->session()->forget('usuario');
        
        // Invalidar sesión completa
        $request->session()->invalidate();
        
        // Regenerar token CSRF
        $request->session()->regenerateToken();
        
        return redirect()->route('inicio')
            ->with('success', 'Sesión cerrada correctamente.');
    }
}