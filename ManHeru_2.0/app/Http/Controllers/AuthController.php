<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

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
        // Validar las credenciales
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El formato del correo electrónico no es válido.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput($request->only('email'));
        }

        try {
            // Buscar usuario por email
            $user = User::where('Gmail', $request->email)->first();

            if (!$user) {
                return back()->withErrors([
                    'email' => 'Las credenciales proporcionadas no son válidas.',
                ])->withInput($request->only('email'));
            }

            // Verificar contraseña
            if (!Hash::check($request->password, $user->Contrasena)) {
                return back()->withErrors([
                    'email' => 'Las credenciales proporcionadas no son válidas.',
                ])->withInput($request->only('email'));
            }

            // Verificar si el usuario está activo
            if ($user->Estatus != 1) {
                return back()->withErrors([
                    'email' => 'Tu cuenta está desactivada. Contacta al administrador.',
                ])->withInput($request->only('email'));
            }

            // Autenticación exitosa: Guardar el usuario en sesión
            session(['usuario' => $user]);
            
            // Log para debugging
            Log::info('Usuario autenticado exitosamente', ['user_id' => $user->ID_Usuario]);

            return redirect()->route('inicio')
                ->with('success', '¡Bienvenido ' . $user->Nombre . '!');

        } catch (\Exception $e) {
            Log::error('Error en login: ' . $e->getMessage());
            return back()->withErrors([
                'email' => 'Error al procesar el inicio de sesión. Intenta nuevamente.',
            ])->withInput($request->only('email'));
        }
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
        // Validación de datos
        $validator = Validator::make($request->all(), [
            'Nombre' => 'required|string|min:2|max:100',
            'Gmail' => 'required|email|max:100|unique:Usuarios,Gmail',
            'Contrasena' => 'required|min:6|confirmed',
            'Contrasena_confirmation' => 'required',
            'Telefono' => 'required|string|min:10|max:20',
        ], [
            'Nombre.required' => 'El nombre es obligatorio.',
            'Nombre.min' => 'El nombre debe tener al menos 2 caracteres.',
            'Nombre.max' => 'El nombre no puede exceder los 100 caracteres.',
            
            'Gmail.required' => 'El correo electrónico es obligatorio.',
            'Gmail.email' => 'El formato del correo no es válido.',
            'Gmail.unique' => 'Este correo ya está registrado.',
            'Gmail.max' => 'El correo no puede exceder los 100 caracteres.',
            
            'Contrasena.required' => 'La contraseña es obligatoria.',
            'Contrasena.min' => 'La contraseña debe tener al menos 6 caracteres.',
            'Contrasena.confirmed' => 'Las contraseñas no coinciden.',
            
            'Contrasena_confirmation.required' => 'Debes confirmar tu contraseña.',
            
            'Telefono.required' => 'El teléfono es obligatorio.',
            'Telefono.min' => 'El teléfono debe tener al menos 10 caracteres.',
            'Telefono.max' => 'El teléfono no puede exceder los 20 caracteres.',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput($request->except('Contrasena', 'Contrasena_confirmation'));
        }

        try {
            // Crear nuevo usuario
            $user = User::create([
                'Nombre' => trim($request->Nombre),
                'Gmail' => strtolower(trim($request->Gmail)),
                'Contrasena' => Hash::make($request->Contrasena),
                'Telefono' => $request->Telefono,
                'ID_Rol' => 2, // Rol de usuario normal por defecto
                'Estatus' => 1, // Activo
            ]);

            // Log para debugging
            Log::info('Usuario registrado exitosamente', [
                'user_id' => $user->ID_Usuario,
                'nombre' => $user->Nombre,
                'email' => $user->Gmail
            ]);

            // Guardar usuario en sesión
            session(['usuario' => $user]);

            return redirect()->route('inicio')
                ->with('success', '¡Cuenta creada exitosamente! Bienvenido ' . $user->Nombre);

        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Error de base de datos en registro: ' . $e->getMessage());
            
            // Verificar si es error de duplicado
            if (str_contains($e->getMessage(), 'Unique constraint')) {
                return back()
                    ->withErrors(['Gmail' => 'Este correo ya está registrado.'])
                    ->withInput($request->except('Contrasena', 'Contrasena_confirmation'));
            }
            
            return back()
                ->withErrors(['error' => 'Error al crear la cuenta. Por favor, verifica tus datos e intenta nuevamente.'])
                ->withInput($request->except('Contrasena', 'Contrasena_confirmation'));
                
        } catch (\Exception $e) {
            Log::error('Error general en registro: ' . $e->getMessage());
            
            return back()
                ->withErrors(['error' => 'Error inesperado al crear la cuenta. Por favor, inténtalo de nuevo.'])
                ->withInput($request->except('Contrasena', 'Contrasena_confirmation'));
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
        
        Log::info('Usuario cerró sesión');
        
        return redirect()->route('inicio')
            ->with('success', 'Sesión cerrada correctamente.');
    }
}