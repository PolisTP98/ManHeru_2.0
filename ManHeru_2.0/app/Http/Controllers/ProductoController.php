<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Tipo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ProductoController extends Controller
{
    /**
     * Mostrar lista de productos (público)
     */
    public function index(Request $request)
    {
        $query = Producto::with('tipo')->activos();

        // Filtrar por categoría si se selecciona
        if ($request->has('categoria') && $request->categoria != '') {
            $query->where('ID_Tipo', $request->categoria);
        }

        // Búsqueda por nombre
        if ($request->has('buscar') && $request->buscar != '') {
            $query->where('Nombre', 'like', '%' . $request->buscar . '%');
        }

        // Ordenamiento
        $ordenamiento = $request->get('orden', 'reciente');
        switch ($ordenamiento) {
            case 'precio_asc':
                $query->orderBy('Precio', 'asc');
                break;
            case 'precio_desc':
                $query->orderBy('Precio', 'desc');
                break;
            case 'nombre':
                $query->orderBy('Nombre', 'asc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        $productos = $query->paginate(12);
        $categorias = Tipo::activos()->get();

        return view('productos', compact('productos', 'categorias'));
    }

    /**
     * Mostrar detalle de un producto
     */
    public function show($id)
    {
        $producto = Producto::with('tipo')->findOrFail($id);
        
        // Productos relacionados (misma categoría)
        $relacionados = Producto::where('ID_Tipo', $producto->ID_Tipo)
            ->where('ID_Producto', '!=', $id)
            ->activos()
            ->limit(4)
            ->get();

        return view('producto-detalle', compact('producto', 'relacionados'));
    }

    /**
     * Mostrar formulario de creación (ADMIN)
     */
    public function create()
    {
        // Verificar que el usuario sea administrador
        if (!session()->has('usuario') || session('usuario')->ID_Rol != 1) {
            return redirect()->route('inicio')
                ->with('error', 'No tienes permisos para acceder a esta página.');
        }

        $categorias = Tipo::activos()->get();
        return view('productos.crear', compact('categorias'));
    }

    /**
     * Guardar nuevo producto (ADMIN)
     */
    public function store(Request $request)
    {
        // Verificar que el usuario sea administrador
        if (!session()->has('usuario') || session('usuario')->ID_Rol != 1) {
            return redirect()->route('inicio')
                ->with('error', 'No tienes permisos para realizar esta acción.');
        }

        // Validación
        $validator = Validator::make($request->all(), [
            'Nombre' => 'required|string|max:100',
            'Descripcion' => 'nullable|string|max:1000',
            'Precio' => 'required|numeric|min:0|max:999999.99',
            'Stock' => 'required|integer|min:0',
            'ID_Tipo' => 'required|exists:Tipos,ID_Tipo',
            'Imagen' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
        ], [
            'Nombre.required' => 'El nombre del producto es obligatorio.',
            'Nombre.max' => 'El nombre no puede exceder los 100 caracteres.',
            'Precio.required' => 'El precio es obligatorio.',
            'Precio.numeric' => 'El precio debe ser un número válido.',
            'Precio.min' => 'El precio no puede ser negativo.',
            'Stock.required' => 'El stock es obligatorio.',
            'Stock.integer' => 'El stock debe ser un número entero.',
            'Stock.min' => 'El stock no puede ser negativo.',
            'ID_Tipo.required' => 'Debes seleccionar una categoría.',
            'ID_Tipo.exists' => 'La categoría seleccionada no es válida.',
            'Imagen.image' => 'El archivo debe ser una imagen.',
            'Imagen.mimes' => 'La imagen debe ser formato: jpeg, jpg, png o gif.',
            'Imagen.max' => 'La imagen no puede pesar más de 2MB.',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Manejar la imagen
            $nombreImagen = null;
            if ($request->hasFile('Imagen')) {
                $imagen = $request->file('Imagen');
                $nombreImagen = time() . '_' . uniqid() . '.' . $imagen->getClientOriginalExtension();
                $imagen->move(public_path('images/productos'), $nombreImagen);
            }

            // Crear producto
            $producto = Producto::create([
                'Nombre' => $request->Nombre,
                'Descripcion' => $request->Descripcion,
                'Precio' => $request->Precio,
                'Stock' => $request->Stock,
                'ID_Tipo' => $request->ID_Tipo,
                'Imagen' => $nombreImagen,
                'Estatus' => 1,
            ]);

            Log::info('Producto creado exitosamente', [
                'producto_id' => $producto->ID_Producto,
                'nombre' => $producto->Nombre,
                'usuario' => session('usuario')->Nombre
            ]);

            return redirect()->route('productos.admin')
                ->with('success', '¡Producto creado exitosamente!');

        } catch (\Exception $e) {
            Log::error('Error al crear producto: ' . $e->getMessage());
            return back()
                ->withErrors(['error' => 'Error al crear el producto. Por favor, inténtalo de nuevo.'])
                ->withInput();
        }
    }

    /**
     * Mostrar formulario de edición (ADMIN)
     */
    public function edit($id)
    {
        // Verificar que el usuario sea administrador
        if (!session()->has('usuario') || session('usuario')->ID_Rol != 1) {
            return redirect()->route('inicio')
                ->with('error', 'No tienes permisos para acceder a esta página.');
        }

        $producto = Producto::findOrFail($id);
        $categorias = Tipo::activos()->get();
        
        return view('productos.editar', compact('producto', 'categorias'));
    }

    /**
     * Actualizar producto (ADMIN)
     */
    public function update(Request $request, $id)
    {
        // Verificar que el usuario sea administrador
        if (!session()->has('usuario') || session('usuario')->ID_Rol != 1) {
            return redirect()->route('inicio')
                ->with('error', 'No tienes permisos para realizar esta acción.');
        }

        $producto = Producto::findOrFail($id);

        // Validación
        $validator = Validator::make($request->all(), [
            'Nombre' => 'required|string|max:100',
            'Descripcion' => 'nullable|string|max:1000',
            'Precio' => 'required|numeric|min:0|max:999999.99',
            'Stock' => 'required|integer|min:0',
            'ID_Tipo' => 'required|exists:Tipos,ID_Tipo',
            'Imagen' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
        ], [
            'Nombre.required' => 'El nombre del producto es obligatorio.',
            'Precio.required' => 'El precio es obligatorio.',
            'Stock.required' => 'El stock es obligatorio.',
            'ID_Tipo.required' => 'Debes seleccionar una categoría.',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Manejar la imagen
            $nombreImagen = $producto->Imagen;
            if ($request->hasFile('Imagen')) {
                // Eliminar imagen anterior si existe
                if ($producto->Imagen && file_exists(public_path('images/productos/' . $producto->Imagen))) {
                    unlink(public_path('images/productos/' . $producto->Imagen));
                }

                // Guardar nueva imagen
                $imagen = $request->file('Imagen');
                $nombreImagen = time() . '_' . uniqid() . '.' . $imagen->getClientOriginalExtension();
                $imagen->move(public_path('images/productos'), $nombreImagen);
            }

            // Actualizar producto
            $producto->update([
                'Nombre' => $request->Nombre,
                'Descripcion' => $request->Descripcion,
                'Precio' => $request->Precio,
                'Stock' => $request->Stock,
                'ID_Tipo' => $request->ID_Tipo,
                'Imagen' => $nombreImagen,
            ]);

            Log::info('Producto actualizado exitosamente', [
                'producto_id' => $producto->ID_Producto,
                'nombre' => $producto->Nombre,
                'usuario' => session('usuario')->Nombre
            ]);

            return redirect()->route('productos.admin')
                ->with('success', '¡Producto actualizado exitosamente!');

        } catch (\Exception $e) {
            Log::error('Error al actualizar producto: ' . $e->getMessage());
            return back()
                ->withErrors(['error' => 'Error al actualizar el producto. Por favor, inténtalo de nuevo.'])
                ->withInput();
        }
    }

    /**
     * Eliminar producto (ADMIN)
     */
    public function destroy($id)
    {
        // Verificar que el usuario sea administrador
        if (!session()->has('usuario') || session('usuario')->ID_Rol != 1) {
            return redirect()->route('inicio')
                ->with('error', 'No tienes permisos para realizar esta acción.');
        }

        try {
            $producto = Producto::findOrFail($id);
            $nombreProducto = $producto->Nombre;

            // Eliminar imagen si existe
            if ($producto->Imagen && file_exists(public_path('images/productos/' . $producto->Imagen))) {
                unlink(public_path('images/productos/' . $producto->Imagen));
            }

            // Eliminar producto
            $producto->delete();

            Log::info('Producto eliminado exitosamente', [
                'producto_id' => $id,
                'nombre' => $nombreProducto,
                'usuario' => session('usuario')->Nombre
            ]);

            return redirect()->route('productos.admin')
                ->with('success', '¡Producto eliminado exitosamente!');

        } catch (\Exception $e) {
            Log::error('Error al eliminar producto: ' . $e->getMessage());
            return back()
                ->with('error', 'Error al eliminar el producto. Por favor, inténtalo de nuevo.');
        }
    }

    /**
     * Cambiar estado del producto (activar/desactivar)
     */
    public function toggleDisponible($id)
    {
        // Verificar que el usuario sea administrador
        if (!session()->has('usuario') || session('usuario')->ID_Rol != 1) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        try {
            $producto = Producto::findOrFail($id);
            $producto->Estatus = !$producto->Estatus;
            $producto->save();

            Log::info('Estado de producto cambiado', [
                'producto_id' => $id,
                'nuevo_estado' => $producto->Estatus,
                'usuario' => session('usuario')->Nombre
            ]);

            return redirect()->back()
                ->with('success', 'Estado del producto actualizado correctamente.');

        } catch (\Exception $e) {
            Log::error('Error al cambiar estado del producto: ' . $e->getMessage());
            return back()
                ->with('error', 'Error al cambiar el estado del producto.');
        }
    }

    /**
     * Panel de administración de productos (ADMIN)
     */
    public function admin(Request $request)
    {
        // Verificar que el usuario sea administrador
        if (!session()->has('usuario') || session('usuario')->ID_Rol != 1) {
            return redirect()->route('inicio')
                ->with('error', 'No tienes permisos para acceder a esta página.');
        }

        $query = Producto::with('tipo');

        // Filtros
        if ($request->has('buscar') && $request->buscar != '') {
            $query->where('Nombre', 'like', '%' . $request->buscar . '%');
        }

        if ($request->has('categoria') && $request->categoria != '') {
            $query->where('ID_Tipo', $request->categoria);
        }

        if ($request->has('estado') && $request->estado != '') {
            $query->where('Estatus', $request->estado);
        }

        $productos = $query->orderBy('created_at', 'desc')->paginate(15);
        $categorias = Tipo::activos()->get();

        return view('productos.admin', compact('productos', 'categorias'));
    }
}