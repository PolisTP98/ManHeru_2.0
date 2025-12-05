<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ManHeRu - Cotizaciones</title>
    <link rel="stylesheet" href="{{ asset('css/inicio.css') }}">
    <link rel="stylesheet" href="{{ asset('css/cotizaciones.css') }}">
    <script>
        // Productos predefinidos para cotización
        const productosPredefinidos = [
            { id: 1, nombre: 'Escritorio ejecutivo de roble', precio: 899.99 },
            { id: 2, nombre: 'Silla ergonómica ejecutiva', precio: 499.99 },
            { id: 3, nombre: 'Estantería modular de oficina', precio: 299.99 },
            { id: 4, nombre: 'Mesa de conferencias grande', precio: 1299.99 },
            { id: 5, nombre: 'Silla para visitas', precio: 199.99 },
            { id: 6, nombre: 'Archivador metálico', precio: 159.99 },
            { id: 7, nombre: 'Escritorio para computadora', precio: 349.99 },
            { id: 8, nombre: 'Mueble para impresora', precio: 249.99 }
        ];

        let cotizacionItems = [];

        // Función para agregar producto
        function agregarProducto() {
            const nombre = document.getElementById('producto-nombre').value;
            const precio = parseFloat(document.getElementById('producto-precio').value);
            const cantidad = parseInt(document.getElementById('producto-cantidad').value);

            if (!nombre || !precio || !cantidad || cantidad <= 0) {
                alert('Por favor, complete todos los campos correctamente.');
                return;
            }

            // Verificar si el producto ya existe en la cotización
            const productoExistente = cotizacionItems.find(item => item.nombre === nombre);
            if (productoExistente) {
                productoExistente.cantidad += cantidad;
                productoExistente.subtotal = productoExistente.cantidad * productoExistente.precio;
            } else {
                const nuevoProducto = {
                    id: Date.now(), // ID único temporal
                    nombre: nombre,
                    precio: precio,
                    cantidad: cantidad,
                    subtotal: precio * cantidad
                };
                cotizacionItems.push(nuevoProducto);
            }

            actualizarTabla();
            calcularTotales();
            limpiarFormulario();
        }

        // Función para seleccionar producto predefinido
        function seleccionarProducto(id) {
            const producto = productosPredefinidos.find(p => p.id === id);
            if (producto) {
                document.getElementById('producto-nombre').value = producto.nombre;
                document.getElementById('producto-precio').value = producto.precio;
                document.getElementById('producto-cantidad').value = 1;
            }
        }

        // Función para actualizar cantidad
        function actualizarCantidad(id, nuevaCantidad) {
            const item = cotizacionItems.find(item => item.id === id);
            if (item && nuevaCantidad > 0) {
                item.cantidad = nuevaCantidad;
                item.subtotal = item.cantidad * item.precio;
                actualizarTabla();
                calcularTotales();
            }
        }

        // Función para eliminar producto
        function eliminarProducto(id) {
            cotizacionItems = cotizacionItems.filter(item => item.id !== id);
            actualizarTabla();
            calcularTotales();
        }

        // Función para actualizar la tabla
        function actualizarTabla() {
            const tbody = document.querySelector('#tabla-cotizacion tbody');
            tbody.innerHTML = '';

            if (cotizacionItems.length === 0) {
                document.getElementById('sin-productos').style.display = 'block';
                document.getElementById('con-productos').style.display = 'none';
                return;
            }

            document.getElementById('sin-productos').style.display = 'none';
            document.getElementById('con-productos').style.display = 'block';

            cotizacionItems.forEach(item => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${item.nombre}</td>
                    <td>
                        <input type="number" 
                               class="cantidad-input" 
                               value="${item.cantidad}" 
                               min="1" 
                               onchange="actualizarCantidad(${item.id}, this.value)">
                    </td>
                    <td>$${item.precio.toFixed(2)}</td>
                    <td>$${item.subtotal.toFixed(2)}</td>
                    <td>
                        <button class="btn-eliminar" onclick="eliminarProducto(${item.id})">
                            Eliminar
                        </button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

        // Función para calcular totales
        function calcularTotales() {
            const subtotal = cotizacionItems.reduce((sum, item) => sum + item.subtotal, 0);
            const iva = subtotal * 0.16; // 16% de IVA
            const total = subtotal + iva;

            document.getElementById('subtotal').textContent = `$${subtotal.toFixed(2)}`;
            document.getElementById('iva').textContent = `$${iva.toFixed(2)}`;
            document.getElementById('total').textContent = `$${total.toFixed(2)}`;
        }

        // Función para limpiar formulario
        function limpiarFormulario() {
            document.getElementById('producto-nombre').value = '';
            document.getElementById('producto-precio').value = '';
            document.getElementById('producto-cantidad').value = '1';
        }

        // Función para limpiar toda la cotización
        function limpiarCotizacion() {
            if (confirm('¿Está seguro de que desea limpiar toda la cotización?')) {
                cotizacionItems = [];
                actualizarTabla();
                calcularTotales();
            }
        }

        // Función para generar cotización
        function generarCotizacion() {
            if (cotizacionItems.length === 0) {
                alert('Agregue al menos un producto para generar la cotización.');
                return;
            }

            // Aquí podrías enviar la cotización al servidor o generar un PDF
            const cliente = document.getElementById('cliente-nombre').value || 'Cliente sin nombre';
            const fecha = new Date().toLocaleDateString('es-ES');
            
            let resumen = `COTIZACIÓN - ${fecha}\n`;
            resumen += `Cliente: ${cliente}\n\n`;
            resumen += 'PRODUCTOS:\n';
            
            cotizacionItems.forEach(item => {
                resumen += `${item.nombre} - ${item.cantidad} x $${item.precio.toFixed(2)} = $${item.subtotal.toFixed(2)}\n`;
            });
            
            resumen += `\nSubtotal: $${document.getElementById('subtotal').textContent}\n`;
            resumen += `IVA (16%): $${document.getElementById('iva').textContent}\n`;
            resumen += `TOTAL: $${document.getElementById('total').textContent}\n\n`;
            resumen += 'Gracias por su preferencia - ManHeRu Mobiliario';
            
            alert(resumen);
            
            // En un caso real, aquí enviarías la cotización al servidor
            console.log('Cotización generada:', {
                cliente: cliente,
                productos: cotizacionItems,
                subtotal: parseFloat(document.getElementById('subtotal').textContent.replace('$', '')),
                iva: parseFloat(document.getElementById('iva').textContent.replace('$', '')),
                total: parseFloat(document.getElementById('total').textContent.replace('$', ''))
            });
        }

        // Inicializar
        document.addEventListener('DOMContentLoaded', function() {
            actualizarTabla();
            calcularTotales();
        });
    </script>
</head>
<body>
    <!-- INCLUIR COMPONENTE DE ALERTAS -->
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
            <a href="{{ route('acerca') }}">Acerca de</a>
            <a href="{{ route('productos') }}">Productos</a>
            <a href="{{ route('cotizaciones') }}">Cotizaciones</a>
            <a href="{{ route('contacto') }}">Contacto</a>
            
            @if(session()->has('usuario'))
                @if(session('usuario')->ID_Rol == 1)
                    <a href="{{ route('usuarios.index') }}">Gestión de Usuarios</a>
                @endif
                <a href="{{ route('logout') }}">Cerrar Sesión</a>
            @else
                <a href="{{ route('login.form') }}">Iniciar Sesión</a>
            @endif
        </nav>
    </header>

    <main class="cotizaciones-container">
        <h1 class="titulo-principal">COTIZACIÓN DE MOBILIARIO</h1>
        <p class="subtitulo">Crea tu cotización personalizada y calcula el total</p>

        <!-- Formulario para agregar productos -->
        <section class="agregar-producto">
            <h2 class="form-titulo">Agregar Producto a la Cotización</h2>
            
            <div class="form-group">
                <label for="cliente-nombre">Nombre del Cliente:</label>
                <input type="text" id="cliente-nombre" class="form-control" placeholder="Ingrese el nombre del cliente">
            </div>
            
            <div class="form-group">
                <label for="producto-nombre">Nombre del Producto:</label>
                <input type="text" id="producto-nombre" class="form-control" placeholder="Ej: Escritorio ejecutivo" required>
            </div>
            
            <div class="form-group">
                <label for="producto-precio">Precio Unitario ($):</label>
                <input type="number" id="producto-precio" class="form-control" placeholder="0.00" min="0" step="0.01" required>
            </div>
            
            <div class="form-group">
                <label for="producto-cantidad">Cantidad:</label>
                <input type="number" id="producto-cantidad" class="form-control" value="1" min="1" required>
            </div>
            
            <button class="btn-agregar" onclick="agregarProducto()">Agregar a Cotización</button>

            <!-- Productos predefinidos -->
            <h3 style="margin-top: 30px; margin-bottom: 15px; color: #333;">Productos Predefinidos:</h3>
            <div class="productos-predefinidos">
                @foreach([
                    ['id' => 1, 'nombre' => 'Escritorio ejecutivo de roble', 'precio' => 899.99],
                    ['id' => 2, 'nombre' => 'Silla ergonómica ejecutiva', 'precio' => 499.99],
                    ['id' => 3, 'nombre' => 'Estantería modular de oficina', 'precio' => 299.99],
                    ['id' => 4, 'nombre' => 'Mesa de conferencias grande', 'precio' => 1299.99],
                    ['id' => 5, 'nombre' => 'Silla para visitas', 'precio' => 199.99],
                    ['id' => 6, 'nombre' => 'Archivador metálico', 'precio' => 159.99],
                    ['id' => 7, 'nombre' => 'Escritorio para computadora', 'precio' => 349.99],
                    ['id' => 8, 'nombre' => 'Mueble para impresora', 'precio' => 249.99]
                ] as $producto)
                    <div class="producto-card">
                        <div class="producto-nombre">{{ $producto['nombre'] }}</div>
                        <div class="producto-precio">${{ number_format($producto['precio'], 2) }}</div>
                        <button class="btn-seleccionar" onclick="seleccionarProducto({{ $producto['id'] }})">
                            Seleccionar
                        </button>
                    </div>
                @endforeach
            </div>
        </section>

        <!-- Tabla de cotización -->
        <section class="tabla-cotizacion">
            <h2 class="form-titulo">Productos en Cotización</h2>
            
            <div id="sin-productos" class="sin-productos">
                <p>No hay productos en la cotización. Agrega productos usando el formulario.</p>
            </div>
            
            <div id="con-productos" style="display: none;">
                <table id="tabla-cotizacion">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Precio Unitario</th>
                            <th>Subtotal</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Los productos se agregarán aquí dinámicamente -->
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Resumen de cotización -->
        <section class="resumen-cotizacion">
            <h2 class="resumen-titulo">Resumen de Cotización</h2>
            
            <div class="total-item">
                <span>Subtotal:</span>
                <span id="subtotal">$0.00</span>
            </div>
            
            <div class="total-item">
                <span>IVA (16%):</span>
                <span id="iva">$0.00</span>
            </div>
            
            <div class="total-item">
                <span>TOTAL:</span>
                <span id="total">$0.00</span>
            </div>
            
            <button class="btn-generar" onclick="generarCotizacion()">Generar Cotización</button>
            <button class="btn-limpiar" onclick="limpiarCotizacion()">Limpiar Cotización</button>
        </section>
    </main>
</body>
</html>