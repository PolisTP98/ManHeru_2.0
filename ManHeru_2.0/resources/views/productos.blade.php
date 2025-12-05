<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos - ManHeRu</title>
    <link rel="stylesheet" href="{{ asset('css/inicio.css') }}">
    <link rel="stylesheet" href="{{ asset('css/productos.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
            <a href="{{ route('inicio') }}#acerca-section">Acerca de</a>
            <a href="{{ route('productos') }}" class="active">Productos</a>
            <a href="{{ route('cotizaciones') }}">Cotizaciones</a>
            <a href="{{ route('contacto') }}">Contacto</a>

            @if(session()->has('usuario'))
                @if(session('usuario')->ID_Rol == 1)
                    <a href="{{ route('usuarios.index') }}">Gestión de Usuarios</a>
                @endif

                <div class="user-profile-dropdown">
                    <button class="profile-btn">
                        <i class="fas fa-user-circle"></i> Mi Perfil
                    </button>
                    <div class="dropdown-content">
                        <a href="{{ route('perfil') }}">
                            <i class="fas fa-user"></i> Ver Perfil
                        </a>
                        <a href="{{ route('perfil.pedidos') }}">
                            <i class="fas fa-shopping-bag"></i> Mis Pedidos
                        </a>
                        <a href="{{ route('logout') }}">
                            <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                        </a>
                    </div>
                </div>
            @else
                <a href="{{ route('login.form') }}">Iniciar Sesión</a>
            @endif
        </nav>
    </header>

    <div class="productos-container">
        <div class="page-header">
            <h1><i class="fas fa-box-open"></i> Nuestros Productos</h1>
            <p>Mobiliario de calidad para tu empresa</p>
        </div>

        <!-- Filtros -->
        <div class="filtros-container">
            <form method="GET" action="{{ route('productos') }}" class="filtros-form">
                <div class="form-group">
                    <label for="buscar">
                        <i class="fas fa-search"></i> Buscar producto
                    </label>
                    <input type="text" 
                           id="buscar" 
                           name="buscar" 
                           placeholder="Nombre del producto..." 
                           value="{{ request('buscar') }}">
                </div>

                <div class="form-group">
                    <label for="categoria">
                        <i class="fas fa-tags"></i> Categoría
                    </label>
                    <select id="categoria" name="categoria">
                        <option value="">Todas las categorías</option>
                        @foreach($categorias as $categoria)
                            <option value="{{ $categoria->ID_Tipo }}" 
                                    {{ request('categoria') == $categoria->ID_Tipo ? 'selected' : '' }}>
                                {{ $categoria->Nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="orden">
                        <i class="fas fa-sort"></i> Ordenar por
                    </label>
                    <select id="orden" name="orden">
                        <option value="reciente" {{ request('orden') == 'reciente' ? 'selected' : '' }}>
                            Más recientes
                        </option>
                        <option value="precio_asc" {{ request('orden') == 'precio_asc' ? 'selected' : '' }}>
                            Precio: Menor a Mayor
                        </option>
                        <option value="precio_desc" {{ request('orden') == 'precio_desc' ? 'selected' : '' }}>
                            Precio: Mayor a Menor
                        </option>
                        <option value="nombre" {{ request('orden') == 'nombre' ? 'selected' : '' }}>
                            Nombre A-Z
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn-filtrar">
                        <i class="fas fa-filter"></i> Filtrar
                    </button>
                </div>

                @if(request()->hasAny(['buscar', 'categoria', 'orden']))
                    <div class="form-group">
                        <a href="{{ route('productos') }}" class="btn-limpiar">
                            <i class="fas fa-times"></i> Limpiar filtros
                        </a>
                    </div>
                @endif
            </form>
        </div>

        @if($productos->count() > 0)
            <!-- Grid de productos -->
            <div class="productos-grid">
                @foreach($productos as $producto)
                    <div class="producto-card">
                        <div class="producto-imagen">
                            @if($producto->Imagen && file_exists(public_path('images/productos/' . $producto->Imagen)))
                                <img src="{{ asset('images/productos/' . $producto->Imagen) }}" 
                                     alt="{{ $producto->Nombre }}"
                                     style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                    <i class="fas fa-image" style="font-size: 4rem; color: rgba(255,255,255,0.5);"></i>
                                </div>
                            @endif
                            
                            @if($producto->Stock < 5 && $producto->Stock > 0)
                                <span class="producto-badge">¡Últimas unidades!</span>
                            @elseif($producto->Stock == 0)
                                <span class="producto-badge" style="background: #dc3545;">Agotado</span>
                            @endif
                        </div>

                        <div class="producto-info">
                            <div class="producto-categoria">
                                <i class="fas fa-tag"></i> {{ $producto->tipo->Nombre }}
                            </div>
                            
                            <h3 class="producto-nombre">{{ $producto->Nombre }}</h3>
                            
                            @if($producto->Descripcion)
                                <p class="producto-descripcion">
                                    {{ Str::limit($producto->Descripcion, 80) }}
                                </p>
                            @endif

                            <div class="producto-footer">
                                <div class="producto-precio">
                                    ${{ number_format($producto->Precio, 2) }}
                                </div>
                                <div class="producto-stock {{ $producto->Stock < 5 ? 'stock-bajo' : '' }} {{ $producto->Stock == 0 ? 'sin-stock' : '' }}">
                                    @if($producto->Stock > 0)
                                        <i class="fas fa-check-circle"></i> Stock: {{ $producto->Stock }}
                                    @else
                                        <i class="fas fa-times-circle"></i> Sin stock
                                    @endif
                                </div>
                            </div>

                            <a href="{{ route('productos.show', $producto->ID_Producto) }}" class="btn-ver-detalle">
                                <i class="fas fa-eye"></i> Ver detalles
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Paginación -->
            <div class="pagination">
                {{ $productos->links() }}
            </div>
        @else
            <!-- Estado vacío -->
            <div class="productos-vacio">
                <i class="fas fa-box-open"></i>
                <h2>No hay productos disponibles</h2>
                <p>
                    @if(request()->hasAny(['buscar', 'categoria']))
                        No se encontraron productos que coincidan con tu búsqueda.
                        <br>Intenta con otros filtros.
                    @else
                        Actualmente no hay productos agregados en nuestro catálogo.
                        <br>¡Pronto tendremos novedades para ti!
                    @endif
                </p>
                <a href="{{ route('inicio') }}" class="btn-volver">
                    <i class="fas fa-home"></i> Volver al inicio
                </a>
            </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Dropdown del perfil
            const profileBtn = document.querySelector('.profile-btn');
            if (profileBtn) {
                profileBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const dropdown = this.nextElementSibling;
                    dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
                });

                document.addEventListener('click', () => {
                    const dropdowns = document.querySelectorAll('.dropdown-content');
                    dropdowns.forEach(drop => drop.style.display = 'none');
                });
            }

            // Animación de aparición de las cards
            const cards = document.querySelectorAll('.producto-card');
            cards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
            });
        });
    </script>
</body>
</html>