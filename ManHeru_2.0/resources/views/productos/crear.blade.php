<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Producto - ManHeRu</title>
    <link rel="stylesheet" href="{{ asset('css/inicio.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/crear.css') }}">
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
            <a href="{{ route('productos.admin') }}">Administrar Productos</a>
            <a href="{{ route('logout') }}">Cerrar Sesión</a>
        </nav>
    </header>

    <div class="form-container">
        <div class="form-header">
            <h1><i class="fas fa-plus-circle"></i> Crear Nuevo Producto</h1>
            <p>Completa la información del producto</p>
        </div>

        @if($errors->any())
            <div class="error-container">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('productos.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="Nombre">Nombre del Producto <span class="required">*</span></label>
                <input type="text" id="Nombre" name="Nombre" 
                       value="{{ old('Nombre') }}" 
                       placeholder="Ej: Escritorio Ejecutivo Premium"
                       required>
                @error('Nombre')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="Descripcion">Descripción</label>
                <textarea id="Descripcion" name="Descripcion" 
                          placeholder="Descripción detallada del producto...">{{ old('Descripcion') }}</textarea>
                @error('Descripcion')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="ID_Tipo">Categoría <span class="required">*</span></label>
                <select id="ID_Tipo" name="ID_Tipo" required>
                    <option value="">Selecciona una categoría</option>
                    @foreach($categorias as $categoria)
                        <option value="{{ $categoria->ID_Tipo }}" 
                                {{ old('ID_Tipo') == $categoria->ID_Tipo ? 'selected' : '' }}>
                            {{ $categoria->Nombre }}
                        </option>
                    @endforeach
                </select>
                @error('ID_Tipo')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="Precio">Precio <span class="required">*</span></label>
                    <input type="number" id="Precio" name="Precio" 
                           value="{{ old('Precio') }}" 
                           step="0.01" 
                           min="0"
                           placeholder="0.00"
                           required>
                    @error('Precio')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="Stock">Stock <span class="required">*</span></label>
                    <input type="number" id="Stock" name="Stock" 
                           value="{{ old('Stock') }}" 
                           min="0"
                           placeholder="0"
                           required>
                    @error('Stock')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="Imagen">Imagen del Producto</label>
                <div class="file-input-wrapper">
                    <input type="file" id="Imagen" name="Imagen" accept="image/*" onchange="previewImage(event)">
                    <label for="Imagen" class="file-input-label">
                        <i class="fas fa-cloud-upload-alt"></i>
                        Seleccionar imagen (JPEG, PNG, GIF - Máx. 2MB)
                    </label>
                </div>
                @error('Imagen')
                    <span class="error-message">{{ $message }}</span>
                @enderror
                
                <div class="preview-container" id="preview-container">
                    <img id="preview" class="preview-image" src="" alt="Vista previa">
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit">
                    <i class="fas fa-save"></i> Guardar Producto
                </button>
                <a href="{{ route('productos.admin') }}" class="btn-cancelar">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>

    <script>
        function previewImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('preview').src = e.target.result;
                    document.getElementById('preview-container').style.display = 'block';
                }
                reader.readAsDataURL(file);
            }
        }
        
        // Ocultar la vista previa inicialmente
        document.getElementById('preview-container').style.display = 'none';
    </script>
</body>
</html>