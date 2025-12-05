<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto - ManHeRu</title>
    <link rel="stylesheet" href="{{ asset('css/inicio.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/editar.css') }}">
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
            <h1><i class="fas fa-edit"></i> Editar Producto</h1>
            <p>Modifica la información del producto</p>
        </div>

        <div class="info-box">
            <i class="fas fa-info-circle"></i>
            <strong>Nota:</strong> Los campos marcados con <span class="required">*</span> son obligatorios.
        </div>

        @if($errors->any())
            <div class="alert alert-error">
                <strong><i class="fas fa-exclamation-triangle"></i> Error en el formulario:</strong>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('productos.update', $producto->ID_Producto) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="Nombre">
                    <i class="fas fa-tag"></i> Nombre del Producto <span class="required">*</span>
                </label>
                <input type="text" 
                       id="Nombre" 
                       name="Nombre" 
                       value="{{ old('Nombre', $producto->Nombre) }}" 
                       placeholder="Ej: Escritorio Ejecutivo Premium"
                       required>
                @error('Nombre')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="Descripcion">
                    <i class="fas fa-align-left"></i> Descripción
                </label>
                <textarea id="Descripcion" 
                          name="Descripcion" 
                          placeholder="Descripción detallada del producto...">{{ old('Descripcion', $producto->Descripcion) }}</textarea>
                @error('Descripcion')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="ID_Tipo">
                    <i class="fas fa-list"></i> Categoría <span class="required">*</span>
                </label>
                <select id="ID_Tipo" name="ID_Tipo" required>
                    <option value="">Selecciona una categoría</option>
                    @foreach($categorias as $categoria)
                        <option value="{{ $categoria->ID_Tipo }}" 
                                {{ old('ID_Tipo', $producto->ID_Tipo) == $categoria->ID_Tipo ? 'selected' : '' }}>
                            {{ $categoria->Nombre }}
                        </option>
                    @endforeach
                </select>
                @error('ID_Tipo')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label for="Precio">
                        <i class="fas fa-dollar-sign"></i> Precio <span class="required">*</span>
                    </label>
                    <input type="number" 
                           id="Precio" 
                           name="Precio" 
                           value="{{ old('Precio', $producto->Precio) }}" 
                           step="0.01" 
                           min="0"
                           placeholder="0.00"
                           required>
                    @error('Precio')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="Stock">
                        <i class="fas fa-boxes"></i> Stock <span class="required">*</span>
                    </label>
                    <input type="number" 
                           id="Stock" 
                           name="Stock" 
                           value="{{ old('Stock', $producto->Stock) }}" 
                           min="0"
                           placeholder="0"
                           required>
                    @error('Stock')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="Imagen">
                    <i class="fas fa-image"></i> Imagen del Producto
                </label>
                
                @if($producto->Imagen && file_exists(public_path('images/productos/' . $producto->Imagen)))
                    <div class="current-image-container">
                        <p><i class="fas fa-check-circle"></i> Imagen actual:</p>
                        <img src="{{ asset('images/productos/' . $producto->Imagen) }}" 
                             class="current-image" 
                             alt="{{ $producto->Nombre }}">
                        <p class="image-note">Deja el campo vacío si no deseas cambiar la imagen</p>
                    </div>
                @else
                    <div class="no-image-alert">
                        <i class="fas fa-exclamation-triangle"></i> Este producto no tiene imagen actualmente
                    </div>
                @endif

                <div class="file-input-wrapper">
                    <input type="file" 
                           id="Imagen" 
                           name="Imagen" 
                           accept="image/*" 
                           onchange="previewImage(event)">
                    <label for="Imagen" class="file-input-label">
                        <i class="fas fa-cloud-upload-alt"></i>
                        Cambiar imagen (JPEG, PNG, GIF - Máx. 2MB)
                    </label>
                </div>
                @error('Imagen')
                    <span class="error-message">{{ $message }}</span>
                @enderror
                
                <div class="preview-container" id="preview-container">
                    <p><i class="fas fa-eye"></i> Vista previa de la nueva imagen:</p>
                    <img id="preview" class="preview-image" src="" alt="Vista previa">
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit">
                    <i class="fas fa-save"></i> Guardar Cambios
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
            const previewContainer = document.getElementById('preview-container');
            const preview = document.getElementById('preview');
            
            if (file) {
                // Validar tamaño del archivo (2MB = 2097152 bytes)
                if (file.size > 2097152) {
                    alert('La imagen es demasiado grande. El tamaño máximo es 2MB.');
                    event.target.value = '';
                    previewContainer.style.display = 'none';
                    return;
                }

                // Validar tipo de archivo
                const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                if (!validTypes.includes(file.type)) {
                    alert('Tipo de archivo no válido. Solo se permiten: JPEG, JPG, PNG, GIF');
                    event.target.value = '';
                    previewContainer.style.display = 'none';
                    return;
                }

                // Mostrar vista previa
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    previewContainer.style.display = 'block';
                    
                    // Scroll suave hacia la vista previa
                    previewContainer.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }
                reader.readAsDataURL(file);
            } else {
                previewContainer.style.display = 'none';
            }
        }

        // Confirmación antes de salir si hay cambios sin guardar
        let formModified = false;
        const formInputs = document.querySelectorAll('input, select, textarea');
        
        formInputs.forEach(input => {
            input.addEventListener('change', () => {
                formModified = true;
            });
        });

        document.querySelector('.btn-cancelar').addEventListener('click', (e) => {
            if (formModified) {
                if (!confirm('¿Estás seguro de cancelar? Los cambios no guardados se perderán.')) {
                    e.preventDefault();
                }
            }
        });

        // Prevenir salida accidental
        window.addEventListener('beforeunload', (e) => {
            if (formModified) {
                e.preventDefault();
                e.returnValue = '';
            }
        });

        // Resetear flag cuando se envía el formulario
        document.querySelector('form').addEventListener('submit', () => {
            formModified = false;
        });

        // Ocultar la vista previa inicialmente
        document.getElementById('preview-container').style.display = 'none';
    </script>
</body>
</html>