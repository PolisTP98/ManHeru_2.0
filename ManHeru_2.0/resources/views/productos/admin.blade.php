<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar Productos - ManHeRu</title>
    <link rel="stylesheet" href="{{ asset('css/inicio.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>
    @include('components.alert')

    <header class="navbar">
        <div class="logo">
            <a href="{{ route('inicio') }}">
                <img src="{{ asset('images/Logo.jpg') }}" alt="Logo ManHeRu">
            </a>
            <span class="nombre">ManHeRu</span>
        </div>

        <nav class="menu">
            <a href="{{ route('inicio') }}">Inicio</a>
            <a href="{{ route('productos') }}">Ver Catálogo</a>
            <a href="{{ route('usuarios.index') }}">Gestión de Usuarios</a>
            <a href="{{ route('logout') }}">Cerrar Sesión</a>
        </nav>
    </header>

    <div class="admin-container">
        <div class="page-header">
            <h1><i class="fas fa-boxes"></i> Administrar Productos</h1>
            <a href="{{ route('productos.create') }}" class="btn-crear">
                <i class="fas fa-plus"></i> Crear Producto
            </a>
        </div>

        <!-- Filtros -->
        <div class="filtros-container">
            <form method="GET" action="{{ route('productos.admin') }}" class="filtros-form">
                <div class="form-group">
                    <label for="buscar"><i class="fas fa-search"></i> Buscar</label>
                    <input type="text" id="buscar" name="buscar" 
                           placeholder="Nombre del producto..." 
                           value="{{ request('buscar') }}">
                </div>

                <div class="form-group">
                    <label for="categoria"><i class="fas fa-tags"></i> Categoría</label>
                    <select id="categoria" name="categoria">
                        <option value="">Todas</option>
                        @foreach($categorias as $categoria)
                            <option value="{{ $categoria->ID_Tipo }}" 
                                    {{ request('categoria') == $categoria->ID_Tipo ? 'selected' : '' }}>
                                {{ $categoria->Nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="estado"><i class="fas fa-filter"></i> Estado</label>
                    <select id="estado" name="estado">
                        <option value="">Todos</option>
                        <option value="1" {{ request('estado') == '1' ? 'selected' : '' }}>Activos</option>
                        <option value="0" {{ request('estado') == '0' ? 'selected' : '' }}>Inactivos</option>
                    </select>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn-filtrar">
                        <i class="fas fa-filter"></i> Filtrar
                    </button>
                </div>
            </form>
        </div>

        <!-- Tabla de productos -->
        <div class="table-container">
            @if($productos->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>Imagen</th>
                            <th>Nombre</th>
                            <th>Categoría</th>
                            <th>Precio</th>
                            <th>Stock</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($productos as $producto)
                            <tr>
                                <td>
                                    @if($producto->Imagen && file_exists(public_path('images/productos/' . $producto->Imagen)))
                                        <img src="{{ asset('images/productos/' . $producto->Imagen) }}" 
                                             alt="{{ $producto->Nombre }}" 
                                             class="producto-img">
                                    @else
                                        <div style="width: 60px; height: 60px; background: #e0e0e0; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-image" style="color: #999;"></i>
                                        </div>
                                    @endif
                                </td>
                                <td><strong>{{ $producto->Nombre }}</strong></td>
                                <td>{{ $producto->tipo->Nombre }}</td>
                                <td><strong>${{ number_format($producto->Precio, 2) }}</strong></td>
                                <td>{{ $producto->Stock }} unidades</td>
                                <td>
                                    <span class="badge {{ $producto->Estatus ? 'badge-activo' : 'badge-inactivo' }}">
                                        {{ $producto->Estatus ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="acciones">
                                        <a href="{{ route('productos.edit', $producto->ID_Producto) }}" 
                                           class="btn-editar">
                                            <i class="fas fa-edit"></i> Editar
                                        </a>
                                        
                                        <form action="{{ route('productos.destroy', $producto->ID_Producto) }}" 
                                              method="POST" 
                                              style="display: inline;"
                                              onsubmit="return confirm('¿Estás seguro de eliminar este producto?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-eliminar">
                                                <i class="fas fa-trash"></i> Eliminar
                                            </button>
                                        </form>

                                        <form action="{{ route('productos.toggle', $producto->ID_Producto) }}" 
                                              method="POST" 
                                              style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn-toggle">
                                                <i class="fas fa-{{ $producto->Estatus ? 'eye-slash' : 'eye' }}"></i>
                                                {{ $producto->Estatus ? 'Desactivar' : 'Activar' }}
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Paginación -->
                <div class="pagination">
                    {{ $productos->links() }}
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-box-open"></i>
                    <h2>No hay productos registrados</h2>
                    <p>Comienza agregando productos a tu catálogo.</p>
                </div>
            @endif
        </div>
    </div>
</body>
</html>