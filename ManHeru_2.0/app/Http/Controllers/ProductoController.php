<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Tipo;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    /**
     * Mostrar lista de productos
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
}