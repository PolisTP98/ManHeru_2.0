<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ManHeRu - Mobiliario para empresas</title>

    <link rel="stylesheet" href="{{ asset('css/inicio.css') }}">
    <link rel="stylesheet" href="{{ asset('css/acerca.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        html {
            scroll-behavior: smooth;
        }
    </style>
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
            <a href="#acerca-section">Acerca de</a>
            <a href="{{ route('productos') }}">Productos</a>
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

    <main class="contenido">
        <section class="texto-principal">
            <h1>Mobiliario Para <br>Empresas</h1>
            <p>
                Creamos espacios de trabajo funcionales y modernos,<br>
                adaptados a las necesidades de tu empresa.
            </p>

            @if(session()->has('usuario'))
                <div style="margin-top: 20px; padding: 15px; background-color: #f8f9fa; border-radius: 5px; border-left: 4px solid #8b0000;">
                    <h3 style="color: #8b0000; margin-bottom: 10px;">Sesión activa</h3>
                    <p><strong>Usuario:</strong> {{ session('usuario')->Nombre }}</p>
                    <p><strong>Email:</strong> {{ session('usuario')->Gmail }}</p>
                    <p><strong>Rol:</strong> 
                        @if(session('usuario')->ID_Rol == 1)
                            Administrador
                        @elseif(session('usuario')->ID_Rol == 2)
                            Usuario
                        @else
                            Invitado
                        @endif
                    </p>
                    <a href="{{ route('perfil') }}" class="btn-perfil" style="display: inline-block; margin-top: 10px; padding: 8px 16px; background-color: #8b0000; color: white; text-decoration: none; border-radius: 4px;">
                        <i class="fas fa-user"></i> Ir a mi perfil
                    </a>
                </div>
            @endif
        </section>
    </main>


    <!-- ========================= -->
    <!--      SECCIÓN ACERCA DE    -->
    <!-- ========================= -->
    <main id="acerca-section" class="acerca-container">

        <section class="acerca-hero">
            <h1>Acerca de ManHeRu</h1>
            <p class="acerca-subtitulo">
                Nos hemos dedicado a ofrecer soluciones en materiales de calidad para empresas 
                de la ciudad de Querétaro. Nuestro compromiso con la innovación y el servicio 
                nos ha permitido alcanzar estos logros:
            </p>
        </section>

        <section class="logros">
            <div class="logro-card">
                <div class="logro-icon">🏆</div>
                <h3>15+ Años de Experiencia</h3>
                <p>Más de una década y media sirviendo a empresas en Querétaro y alrededores.</p>
            </div>
            
            <div class="logro-card">
                <div class="logro-icon">🤝</div>
                <h3>500+ Clientes Satisfechos</h3>
                <p>Empresas de todos los tamaños confían en nuestros productos y servicios.</p>
            </div>
            
            <div class="logro-card">
                <div class="logro-icon">📈</div>
                <h3>Crecimiento Continuo</h3>
                <p>Expandimos nuestro catálogo y servicios para adaptarnos a las necesidades del mercado.</p>
            </div>
        </section>

        <section class="vision-mision">
            <div class="vmv-card">
                <h2><span class="vmv-icon">👁️</span> Visión</h2>
                <p>Ser la empresa líder en soluciones de mobiliario para empresas en México, reconocida por nuestra calidad, innovación y servicio al cliente.</p>
            </div>
            
            <div class="vmv-card">
                <h2><span class="vmv-icon">🎯</span> Misión</h2>
                <p>Proveer mobiliario de alta calidad que optimice los espacios de trabajo y fomente la productividad y bienestar de nuestros clientes.</p>
            </div>
        </section>

        <section class="valores">
            <h2><span class="valores-icon">❤️</span> Valores</h2>
            <div class="valores-grid">
                <div class="valor-item">
                    <h3>Calidad</h3>
                    <p>Productos duraderos y funcionales.</p>
                </div>
                
                <div class="valor-item">
                    <h3>Innovación</h3>
                    <p>Implementación de tendencias modernas en diseño.</p>
                </div>
                
                <div class="valor-item">
                    <h3>Compromiso</h3>
                    <p>Satisfacción total de las necesidades del cliente.</p>
                </div>
                
                <div class="valor-item">
                    <h3>Integridad</h3>
                    <p>Honestidad y transparencia en nuestras operaciones.</p>
                </div>
            </div>
        </section>

        <section class="cta-section">
            <h2>¿Listo para transformar tu espacio de trabajo?</h2>
            <p>Contáctanos hoy mismo y descubre cómo podemos ayudarte.</p>
            <a href="{{ route('inicio') }}" class="btn-volver">Volver arriba</a>
        </section>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
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
        });
    </script>

</body>
</html>
