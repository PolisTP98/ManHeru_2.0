<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UsuarioController extends Controller
{
    /**
     * Mostrar lista de usuarios con búsqueda
     */
    public function index(Request $request)
    {
        $query = User::query();
        
        // Búsqueda
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('Nombre', 'LIKE', "%{$search}%")
                  ->orWhere('Gmail', 'LIKE', "%{$search}%")
                  ->orWhere('Telefono', 'LIKE', "%{$search}%")
                  ->orWhere('ID_Usuario', 'LIKE', "%{$search}%");
            });
        }
        
        // Filtro por rol
        if ($request->has('rol') && !empty($request->rol)) {
            $query->where('ID_Rol', $request->rol);
        }
        
        // Filtro por estatus
        if ($request->has('estatus') && $request->estatus !== '') {
            $query->where('Estatus', $request->estatus);
        }
        
        $usuarios = $query->orderBy('ID_Usuario', 'desc')->get();
        
        return view('usuarios.index', compact('usuarios'));
    }

    /**
     * Mostrar formulario para crear nuevo usuario
     */
    public function create()
    {
        return view('usuarios.create');
    }

    /**
     * Almacenar nuevo usuario
     */
    public function store(Request $request)
    {
        $request->validate([
            'Nombre' => 'required|string|max:100',
            'Gmail' => 'required|email|max:255|unique:Usuarios,Gmail',
            'Contrasena' => 'required|min:6',
            'Telefono' => 'required|string|max:20',
            'ID_Rol' => 'required|integer',
        ]);

        try {
            $usuario = User::create([
                'Nombre' => $request->Nombre,
                'Gmail' => $request->Gmail,
                'Contrasena' => Hash::make($request->Contrasena),
                'Telefono' => $request->Telefono,
                'ID_Rol' => $request->ID_Rol,
                'Estatus' => 1, // Activo por defecto
            ]);

            return redirect()->route('usuarios.index')
                ->with('success', 'Usuario creado exitosamente');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al crear usuario: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Mostrar formulario para editar usuario
     */
    public function edit($id)
    {
        $usuario = User::findOrFail($id);
        return view('usuarios.edit', compact('usuario'));
    }

    /**
     * Actualizar usuario
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'Nombre' => 'required|string|max:100',
            'Gmail' => 'required|email|max:255|unique:Usuarios,Gmail,' . $id . ',ID_Usuario',
            'Telefono' => 'required|string|max:20',
            'ID_Rol' => 'required|integer',
        ]);

        try {
            $usuario = User::findOrFail($id);
            
            $data = [
                'Nombre' => $request->Nombre,
                'Gmail' => $request->Gmail,
                'Telefono' => $request->Telefono,
                'ID_Rol' => $request->ID_Rol,
            ];
            
            // Si se proporciona nueva contraseña, actualizarla
            if ($request->filled('Contrasena')) {
                $request->validate([
                    'Contrasena' => 'min:6',
                ]);
                $data['Contrasena'] = Hash::make($request->Contrasena);
            }
            
            $usuario->update($data);

            return redirect()->route('usuarios.index')
                ->with('success', 'Usuario actualizado exitosamente');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al actualizar usuario: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Eliminar usuario
     */
    public function destroy($id)
    {
        try {
            // No permitir eliminar el usuario actual
            if (session()->has('usuario') && session('usuario')->ID_Usuario == $id) {
                return redirect()->route('usuarios.index')
                    ->with('error', 'No puedes eliminar tu propio usuario');
            }
            
            $usuario = User::findOrFail($id);
            $usuario->delete();

            return redirect()->route('usuarios.index')
                ->with('success', 'Usuario eliminado exitosamente');
                
        } catch (\Exception $e) {
            return redirect()->route('usuarios.index')
                ->with('error', 'Error al eliminar usuario: ' . $e->getMessage());
        }
    }

    /**
     * Activar/Desactivar usuario
     */
    public function toggleStatus($id)
    {
        try {
            $usuario = User::findOrFail($id);
            $usuario->Estatus = $usuario->Estatus == 1 ? 0 : 1;
            $usuario->save();

            $status = $usuario->Estatus == 1 ? 'activado' : 'desactivado';
            
            return redirect()->route('usuarios.index')
                ->with('success', "Usuario {$status} exitosamente");
                
        } catch (\Exception $e) {
            return redirect()->route('usuarios.index')
                ->with('error', 'Error al cambiar estatus: ' . $e->getMessage());
        }
    }
}