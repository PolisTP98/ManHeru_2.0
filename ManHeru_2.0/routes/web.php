<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\FavoritoController;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Rutas Web
|--------------------------------------------------------------------------
*/

// ============================================================================
// PÁGINA DE INICIO
// ============================================================================
Route::get('/', function () {
    if (session()->has('usuario')) {
        $usuario = session('usuario');
        return view('Inicio', compact('usuario'));
    }
    return view('Inicio');
})->name('inicio');

// ============================================================================
// RUTAS DE AUTENTICACIÓN
// ============================================================================
Route::get('/login', [AuthController::class, 'showLogin'])->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/registro', [AuthController::class, 'showRegister'])->name('register.form');
Route::post('/registro', [AuthController::class, 'register'])->name('register');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// ============================================================================
// RUTAS PROTEGIDAS (REQUIEREN AUTENTICACIÓN)
// ============================================================================
Route::middleware(['auth.session'])->group(function () {
    
    // Perfil de usuario
    Route::get('/perfil', [PerfilController::class, 'index'])->name('perfil');
    Route::get('/perfil/pedidos', [PerfilController::class, 'pedidos'])->name('perfil.pedidos');
    Route::get('/perfil/pedido/{id}', [PerfilController::class, 'verPedido'])->name('perfil.pedido');
    
    // Carrito de compras
    Route::get('/carrito', [CarritoController::class, 'index'])->name('carrito');
    Route::post('/carrito/agregar', [CarritoController::class, 'agregar'])->name('carrito.agregar');
    Route::post('/carrito/actualizar', [CarritoController::class, 'actualizar'])->name('carrito.actualizar');
    Route::post('/carrito/eliminar', [CarritoController::class, 'eliminar'])->name('carrito.eliminar');
    Route::post('/carrito/guardar-ubicacion', [CarritoController::class, 'guardarUbicacion'])->name('carrito.guardar-ubicacion');
    Route::post('/carrito/procesar-pago', [CarritoController::class, 'procesarPago'])->name('carrito.procesar-pago');
    Route::get('/carrito/comprobante/{id}', [CarritoController::class, 'descargarComprobante'])->name('carrito.comprobante');
    
    // Favoritos
    Route::get('/favoritos', [FavoritoController::class, 'index'])->name('favoritos');
    Route::post('/favoritos/agregar', [FavoritoController::class, 'agregar'])->name('favoritos.agregar');
    Route::post('/favoritos/eliminar', [FavoritoController::class, 'eliminar'])->name('favoritos.eliminar');
    
    // Gestión de productos (solo administradores)
    Route::get('/productos/crear', [ProductoController::class, 'create'])->name('productos.create');
    Route::post('/productos/store', [ProductoController::class, 'store'])->name('productos.store');
    Route::get('/productos/{id}/editar', [ProductoController::class, 'edit'])->name('productos.edit');
    Route::put('/productos/{id}', [ProductoController::class, 'update'])->name('productos.update');
    Route::delete('/productos/{id}', [ProductoController::class, 'destroy'])->name('productos.destroy');
    Route::post('/productos/{id}/toggle-disponible', [ProductoController::class, 'toggleDisponible'])->name('productos.toggle');
});

// ============================================================================
// CRUD DE USUARIOS (SOLO ADMINISTRADORES)
// ============================================================================
Route::middleware(['auth.session', 'admin'])->group(function () {
    Route::resource('usuarios', UsuarioController::class)->except(['show']);
    Route::post('/usuarios/{id}/toggle-status', [UsuarioController::class, 'toggleStatus'])
        ->name('usuarios.toggle-status');
});

// ============================================================================
// RUTAS PÚBLICAS DE PRODUCTOS
// ============================================================================
Route::get('/productos', [ProductoController::class, 'index'])->name('productos');
Route::get('/productos/{id}', [ProductoController::class, 'show'])->name('productos.show');

// ============================================================================
// PÁGINAS ESTÁTICAS
// ============================================================================
Route::get('/acerca', function () {
    return view('acerca');
})->name('acerca');

Route::get('/cotizaciones', function () {
    return view('cotizaciones');
})->name('cotizaciones');

// ============================================================================
// CONTACTO
// ============================================================================
Route::get('/contacto', function () {
    return view('contacto');
})->name('contacto');

Route::post('/contacto/enviar', function (Request $request) {
    // Validación de datos
    $validated = $request->validate([
        'nombre' => 'required|string|max:100',
        'email' => 'required|email|max:100',
        'telefono' => 'nullable|string|max:20',
        'empresa' => 'nullable|string|max:100',
        'asunto' => 'required|string',
        'prioridad' => 'required|in:baja,media,alta',
        'mensaje' => 'required|string|min:10|max:1000'
    ]);
    
    // Aquí iría la lógica para enviar el correo al encargado de distribución
    // Por ejemplo:
    // Mail::to('distribucion@manheru.com')->send(new ContactoMensaje($validated));
    
    // Por ahora, solo guardamos en sesión para mostrar
    $mensajes = session('contacto_mensajes', []);
    $mensajes[] = [
        'id' => count($mensajes) + 1,
        'fecha' => now(),
        'datos' => $validated
    ];
    session(['contacto_mensajes' => $mensajes]);
    
    return redirect()->route('contacto')
        ->with('success', '¡Mensaje enviado correctamente! El encargado de distribución se pondrá en contacto contigo pronto.');
})->name('contacto.enviar');