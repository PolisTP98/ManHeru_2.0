<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios - ManHeRu</title>
    <link rel="stylesheet" href="{{ asset('css/usuarios.css') }}">
    <style>
        .search-container {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .search-form {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            align-items: center;
        }
        
        .search-form input,
        .search-form select {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            flex: 1;
            min-width: 200px;
        }
        
        .search-form button {
            white-space: nowrap;
        }
        
        .filters-row {
            display: flex;
            gap: 10px;
            margin-top: 10px;
            flex-wrap: wrap;
        }
        
        .actions {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
        }
        
        .btn-status {
            padding: 4px 8px;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            font-size: 12px;
            font-weight: bold;
            min-width: 80px;
        }
        
        .btn-active {
            background-color: #d4edda;
            color: #155724;
        }
        
        .btn-inactive {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    @include('components.alert')

    <div class="crud-container">
        <div class="crud-header">
            <h1>Gestión de Usuarios</h1>
            <a href="{{ route('usuarios.create') }}" class="btn btn-primary">Nuevo Usuario</a>
            <a href="{{ route('inicio') }}" class="btn btn-secondary">Volver al Inicio</a>
        </div>

        <!-- Barra de búsqueda -->
        <div class="search-container">
            <form action="{{ route('usuarios.index') }}" method="GET" class="search-form">
                <input type="text" 
                       name="search" 
                       placeholder="Buscar por nombre, email, teléfono o ID..."
                       value="{{ request('search') }}"
                       autocomplete="off">
                <button type="submit" class="btn btn-primary">Buscar</button>
                @if(request()->has('search') || request()->has('rol') || request()->has('estatus'))
                    <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Limpiar</a>
                @endif
            </form>
            
            <div class="filters-row">
                <select name="rol" form="filterForm" onchange="this.form.submit()">
                    <option value="">Todos los roles</option>
                    <option value="1" {{ request('rol') == '1' ? 'selected' : '' }}>Administrador</option>
                    <option value="2" {{ request('rol') == '2' ? 'selected' : '' }}>Usuario</option>
                    <option value="3" {{ request('rol') == '3' ? 'selected' : '' }}>Invitado</option>
                </select>
                
                <select name="estatus" form="filterForm" onchange="this.form.submit()">
                    <option value="">Todos los estados</option>
                    <option value="1" {{ request('estatus') == '1' ? 'selected' : '' }}>Activos</option>
                    <option value="0" {{ request('estatus') == '0' ? 'selected' : '' }}>Inactivos</option>
                </select>
            </div>
            
            <form id="filterForm" action="{{ route('usuarios.index') }}" method="GET" style="display: none;">
                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif
            </form>
        </div>

        <div class="table-container">
            @if($usuarios->count() > 0)
            <table class="crud-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Rol</th>
                        <th>Estatus</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($usuarios as $usuario)
                    <tr>
                        <td>{{ $usuario->ID_Usuario }}</td>
                        <td>{{ $usuario->Nombre }}</td>
                        <td>{{ $usuario->Gmail }}</td>
                        <td>{{ $usuario->Telefono }}</td>
                        <td>
                            @if($usuario->ID_Rol == 1)
                                <span class="badge badge-admin">Administrador</span>
                            @elseif($usuario->ID_Rol == 2)
                                <span class="badge badge-user">Usuario</span>
                            @else
                                <span class="badge badge-guest">Invitado</span>
                            @endif
                        </td>
                        <td>
                            @if($usuario->Estatus == 1)
                                <form action="{{ route('usuarios.toggle-status', $usuario->ID_Usuario) }}" 
                                      method="POST" 
                                      style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn-status btn-active">
                                        Activo
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('usuarios.toggle-status', $usuario->ID_Usuario) }}" 
                                      method="POST" 
                                      style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn-status btn-inactive">
                                        Inactivo
                                    </button>
                                </form>
                            @endif
                        </td>
                        <td class="actions">
                            <a href="{{ route('usuarios.edit', $usuario->ID_Usuario) }}" 
                               class="btn btn-edit">
                               Editar
                            </a>
                            <form action="{{ route('usuarios.destroy', $usuario->ID_Usuario) }}" 
                                  method="POST" 
                                  class="delete-form"
                                  onsubmit="return confirmDelete('{{ $usuario->Nombre }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-delete">
                                    Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="no-results">
                <p>No se encontraron usuarios.</p>
                @if(request()->has('search') || request()->has('rol') || request()->has('estatus'))
                    <a href="{{ route('usuarios.index') }}" class="btn btn-primary">Ver todos los usuarios</a>
                @endif
            </div>
            @endif
        </div>
    </div>

    <script>
        function confirmDelete(nombre) {
            return confirm(`¿Estás seguro de eliminar al usuario "${nombre}"?\nEsta acción no se puede deshacer.`);
        }
        
        // Enviar formulario de filtros cuando se cambie un select
        document.addEventListener('DOMContentLoaded', function() {
            const filterForm = document.getElementById('filterForm');
            const searchInput = document.querySelector('input[name="search"]');
            
            if (searchInput && filterForm) {
                searchInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        const searchValue = this.value;
                        const hiddenInput = filterForm.querySelector('input[name="search"]');
                        if (hiddenInput) {
                            hiddenInput.value = searchValue;
                        }
                        filterForm.submit();
                    }
                });
            }
        });
    </script>
</body>
</html>