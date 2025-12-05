<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // Asegúrate de que este path sea correcto
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log; // Importar para depuración

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
        // 1. Validar las credenciales
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ], [
            // Mensajes personalizados de error para el usuario
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El formato del correo electrónico no es válido.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos :min caracteres.',
        ]);

        // 2. Buscar usuario por email (Gmail en tu BD)
        // Utiliza 'Gmail' porque es el nombre de la columna en tu tabla Usuarios
        $user = User::where('Gmail', $request->email)->first();

        if ($user) {
            // 3. Verificar contraseña (Contrasena en tu BD)
            // Hash::check compara el texto plano (input) con el hash guardado (DB)
            if (Hash::check($request->password, $user->Contrasena)) {
                
                // 4. Verificar si el usuario está activo (Estatus = 1)
                if ($user->Estatus == 1) {
                    
                    // 5. Autenticación exitosa: Guardar el usuario en sesión
                    session(['usuario' => $user]);
                    
                    // 6. Redirigir al inicio
                    return redirect()->route('inicio')
                        ->with('success', '¡Bienvenido ' . $user->Nombre . '!');
                } else {
                    // La cuenta está inactiva
                    return back()->withErrors([
                        'email' => 'Tu cuenta está desactivada. Contacta al administrador.',
                    ])->withInput($request->only('email', 'remember'));
                }
            }
        }

        // Si no se encontró el usuario o la contraseña es incorrecta
        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no son válidas.',
        ])->withInput($request->only('email', 'remember'));
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
            'Gmail' => 'required|email|unique:Usuarios,Gmail', // Validar unicidad en la tabla 'Usuarios'
            'Contrasena' => 'required|min:6|confirmed', // Asegúrate de que el campo de input en el formulario sea 'Contrasena'
            'Contrasena_confirmation' => 'required',
            'Telefono' => 'required|string|max:20',
        ]);

        try {
            // Crear nuevo usuario usando el modelo User
            $user = User::create([
                'Nombre' => $request->Nombre,
                'Gmail' => $request->Gmail,
                // Usamos Hash::make para guardar la contraseña encriptada
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
            Log::error("Error de registro: " . $e->getMessage());
            return back()->withErrors([
                'error' => 'Error al crear la cuenta. Por favor, inténtalo de nuevo.',
            ])->withInput();
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