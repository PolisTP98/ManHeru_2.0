<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacto - ManHeRu</title>
    <link rel="stylesheet" href="{{ asset('css/inicio.css') }}">
    <link rel="stylesheet" href="{{ asset('css/contacto.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
                
                <!-- Carrito de compras -->
                <a href="{{ route('carrito') }}" class="carrito-link" id="carrito-header">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="carrito-contador" id="carrito-contador">
                        {{ session('carrito_count', 0) }}
                    </span>
                </a>
                
                <!-- Dropdown del perfil -->
                <div class="user-profile-dropdown">
                    <button class="profile-btn">
                        <i class="fas fa-user-circle"></i> {{ session('usuario')->Nombre }}
                    </button>
                    <div class="dropdown-content">
                        <a href="{{ route('perfil') }}">
                            <i class="fas fa-user"></i> Mi Perfil
                        </a>
                        <a href="{{ route('perfil.pedidos') }}">
                            <i class="fas fa-shopping-bag"></i> Mis Pedidos
                        </a>
                        <a href="{{ route('carrito') }}">
                            <i class="fas fa-shopping-cart"></i> Mi Carrito
                        </a>
                        <a href="{{ route('favoritos') }}">
                            <i class="fas fa-heart"></i> Favoritos
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

    <main class="contacto-container">
        <div class="contacto-header">
            <h1><i class="fas fa-headset"></i> Contáctanos</h1>
            <p class="subtitulo">Comunícate con nuestro equipo de distribución</p>
            <p class="descripcion">
                ¿Tienes preguntas sobre nuestros productos, distribución o necesitas soporte? 
                Nuestro equipo de expertos está listo para ayudarte.
            </p>
        </div>

        <div class="contacto-grid">
            <!-- Información de contacto -->
            <div class="contacto-info">
                <h2><i class="fas fa-info-circle"></i> Información de Contacto</h2>
                
                <div class="info-card">
                    <div class="info-icon">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <div class="info-content">
                        <h3>Encargado de Distribución</h3>
                        <p><strong>Nombre:</strong> Ing. Carlos Rodríguez</p>
                        <p><strong>Teléfono:</strong> (442) 123-4567 ext. 101</p>
                        <p><strong>Email:</strong> distribucion@manheru.com</p>
                    </div>
                </div>
                
                <div class="info-card">
                    <div class="info-icon">
                        <i class="fas fa-warehouse"></i>
                    </div>
                    <div class="info-content">
                        <h3>Centro de Distribución</h3>
                        <p><strong>Dirección:</strong> Av. Tecnológico #123, Parque Industrial, Querétaro, Qro. 76100</p>
                        <p><strong>Horario:</strong> Lunes a Viernes: 8:00 AM - 6:00 PM</p>
                        <p><strong>Teléfono:</strong> (442) 987-6543</p>
                    </div>
                </div>
                
                <div class="info-card">
                    <div class="info-icon">
                        <i class="fas fa-shipping-fast"></i>
                    </div>
                    <div class="info-content">
                        <h3>Soporte Logístico</h3>
                        <p><strong>Encargado:</strong> Lic. Ana Martínez</p>
                        <p><strong>Email:</strong> logistica@manheru.com</p>
                        <p><strong>Teléfono:</strong> (442) 456-7890 ext. 102</p>
                    </div>
                </div>
                
                <div class="info-card">
                    <div class="info-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="info-content">
                        <h3>Zonas de Cobertura</h3>
                        <p>• Querétaro y área metropolitana</p>
                        <p>• Bajío (Guanajuato, León, San Miguel)</p>
                        <p>• Centro de México</p>
                        <p>• Entregas nacionales bajo solicitud</p>
                    </div>
                </div>
            </div>

            <!-- Formulario de contacto -->
            <div class="contacto-formulario">
                <h2><i class="fas fa-envelope"></i> Envíanos un Mensaje</h2>
                <p class="form-descripcion">Completa el formulario y nos pondremos en contacto contigo en menos de 24 horas.</p>
                
                <form id="form-contacto" method="POST" action="{{ route('contacto.enviar') }}">
                    @csrf
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="nombre"><i class="fas fa-user"></i> Nombre Completo *</label>
                            <input type="text" id="nombre" name="nombre" required 
                                   placeholder="Ingresa tu nombre completo"
                                   value="{{ session()->has('usuario') ? session('usuario')->Nombre : '' }}">
                        </div>
                        
                        <div class="form-group">
                            <label for="email"><i class="fas fa-envelope"></i> Correo Electrónico *</label>
                            <input type="email" id="email" name="email" required 
                                   placeholder="ejemplo@correo.com"
                                   value="{{ session()->has('usuario') ? session('usuario')->Gmail : '' }}">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="telefono"><i class="fas fa-phone"></i> Teléfono</label>
                            <input type="tel" id="telefono" name="telefono" 
                                   placeholder="(442) 123-4567"
                                   value="{{ session()->has('usuario') ? session('usuario')->Telefono : '' }}">
                        </div>
                        
                        <div class="form-group">
                            <label for="empresa"><i class="fas fa-building"></i> Empresa (opcional)</label>
                            <input type="text" id="empresa" name="empresa" 
                                   placeholder="Nombre de tu empresa">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="asunto"><i class="fas fa-tag"></i> Asunto *</label>
                        <select id="asunto" name="asunto" required>
                            <option value="">Selecciona un asunto</option>
                            <option value="distribucion">Consulta de distribución</option>
                            <option value="cotizacion">Solicitud de cotización</option>
                            <option value="soporte">Soporte técnico</option>
                            <option value="ventas">Información de ventas</option>
                            <option value="pedido">Seguimiento de pedido</option>
                            <option value="otro">Otro</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="prioridad"><i class="fas fa-exclamation-circle"></i> Prioridad</label>
                        <div class="prioridad-opciones">
                            <label class="prioridad-label">
                                <input type="radio" name="prioridad" value="baja" checked>
                                <span class="prioridad-baja">Baja</span>
                            </label>
                            <label class="prioridad-label">
                                <input type="radio" name="prioridad" value="media">
                                <span class="prioridad-media">Media</span>
                            </label>
                            <label class="prioridad-label">
                                <input type="radio" name="prioridad" value="alta">
                                <span class="prioridad-alta">Alta</span>
                            </label>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="mensaje"><i class="fas fa-comment-dots"></i> Mensaje *</label>
                        <textarea id="mensaje" name="mensaje" rows="6" required 
                                  placeholder="Describe detalladamente tu consulta..."></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="copia" checked>
                            <span>Enviarme una copia de este mensaje</span>
                        </label>
                    </div>
                    
                    <div class="form-actions">
                        <button type="reset" class="btn-reset">
                            <i class="fas fa-redo"></i> Limpiar
                        </button>
                        <button type="submit" class="btn-enviar">
                            <i class="fas fa-paper-plane"></i> Enviar Mensaje
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Información adicional -->
        <div class="info-adicional">
            <h2><i class="fas fa-question-circle"></i> Preguntas Frecuentes</h2>
            
            <div class="faq-grid">
                <div class="faq-item">
                    <h3><i class="fas fa-shipping-fast"></i> ¿Cuál es el tiempo de entrega?</h3>
                    <p>En Querétaro: 24-48 horas hábiles. Otras ubicaciones: 3-5 días hábiles según la zona.</p>
                </div>
                
                <div class="faq-item">
                    <h3><i class="fas fa-box"></i> ¿Realizan entregas a toda la república?</h3>
                    <p>Sí, contamos con alianzas logísticas para entregas nacionales. Consulta costos y tiempos.</p>
                </div>
                
                <div class="faq-item">
                    <h3><i class="fas fa-tools"></i> ¿Ofrecen servicio de instalación?</h3>
                    <p>Sí, contamos con equipo especializado para instalación profesional de mobiliario.</p>
                </div>
                
                <div class="faq-item">
                    <h3><i class="fas fa-undo"></i> ¿Cuál es la política de devoluciones?</h3>
                    <p>15 días para devoluciones. Producto debe estar en perfecto estado y en empaque original.</p>
                </div>
            </div>
        </div>

        <!-- Mapa de ubicación -->
        <div class="ubicacion-mapa">
            <h2><i class="fas fa-map-marked-alt"></i> Ubicación de Nuestro Centro de Distribución</h2>
            <div class="mapa-contenedor">
                <!-- Aquí iría un mapa de Google Maps -->
                <div class="mapa-placeholder">
                    <i class="fas fa-map-marker-alt"></i>
                    <h3>Av. Tecnológico #123, Parque Industrial</h3>
                    <p>Querétaro, Qro. 76100</p>
                    <p class="mapa-nota">(En una implementación real, aquí iría un mapa interactivo de Google Maps)</p>
                </div>
            </div>
            
            <div class="mapa-info">
                <div class="mapa-dato">
                    <i class="fas fa-clock"></i>
                    <div>
                        <h4>Horario de Atención</h4>
                        <p>Lunes a Viernes: 8:00 AM - 6:00 PM</p>
                        <p>Sábados: 9:00 AM - 2:00 PM</p>
                    </div>
                </div>
                
                <div class="mapa-dato">
                    <i class="fas fa-phone"></i>
                    <div>
                        <h4>Teléfonos</h4>
                        <p>Distribución: (442) 123-4567</p>
                        <p>Ventas: (442) 987-6543</p>
                    </div>
                </div>
                
                <div class="mapa-dato">
                    <i class="fas fa-envelope"></i>
                    <div>
                        <h4>Correos Electrónicos</h4>
                        <p>distribucion@manheru.com</p>
                        <p>ventas@manheru.com</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Dropdown del perfil
            const profileBtn = document.querySelector('.profile-btn');
            if (profileBtn) {
                profileBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const dropdown = this.nextElementSibling;
                    dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
                });
                
                document.addEventListener('click', function() {
                    const dropdowns = document.querySelectorAll('.dropdown-content');
                    dropdowns.forEach(dropdown => {
                        dropdown.style.display = 'none';
                    });
                });
            }

            // Formulario de contacto
            const formulario = document.getElementById('form-contacto');
            if (formulario) {
                formulario.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    // Validación básica
                    const nombre = document.getElementById('nombre').value.trim();
                    const email = document.getElementById('email').value.trim();
                    const mensaje = document.getElementById('mensaje').value.trim();
                    
                    if (!nombre || !email || !mensaje) {
                        mostrarNotificacion('Por favor, completa todos los campos obligatorios.', 'error');
                        return;
                    }
                    
                    // Simular envío (en producción, sería una petición AJAX real)
                    mostrarNotificacion('Enviando mensaje...', 'info');
                    
                    setTimeout(() => {
                        // Mostrar mensaje de éxito
                        mostrarNotificacion('¡Mensaje enviado correctamente! Te contactaremos pronto.', 'success');
                        
                        // Resetear formulario (excepto datos de usuario)
                        if (!{{ session()->has('usuario') ? 'true' : 'false' }}) {
                            formulario.reset();
                        } else {
                            // Mantener datos del usuario
                            document.getElementById('mensaje').value = '';
                            document.getElementById('empresa').value = '';
                            document.getElementById('asunto').selectedIndex = 0;
                        }
                    }, 1500);
                });
            }

            // Animación de las tarjetas de información
            const infoCards = document.querySelectorAll('.info-card');
            infoCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-5px)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });

            // FAQ acordeón (opcional)
            const faqItems = document.querySelectorAll('.faq-item h3');
            faqItems.forEach(item => {
                item.addEventListener('click', function() {
                    const content = this.nextElementSibling;
                    content.style.display = content.style.display === 'none' ? 'block' : 'none';
                });
            });
        });

        function mostrarNotificacion(mensaje, tipo) {
            // Eliminar notificaciones anteriores
            const notificacionesAnteriores = document.querySelectorAll('.notificacion-contacto');
            notificacionesAnteriores.forEach(notif => notif.remove());
            
            // Crear nueva notificación
            const notificacion = document.createElement('div');
            notificacion.className = `notificacion-contacto notificacion-${tipo}`;
            notificacion.innerHTML = `
                <span>${mensaje}</span>
                <button onclick="this.parentElement.remove()">&times;</button>
            `;
            
            document.body.appendChild(notificacion);
            
            // Remover después de 5 segundos
            setTimeout(() => {
                if (notificacion.parentElement) {
                    notificacion.remove();
                }
            }, 5000);
        }
    </script>
</body>
</html>