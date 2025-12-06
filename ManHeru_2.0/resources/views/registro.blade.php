<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - ManHeRu</title>
    <link rel="stylesheet" href="{{ asset('css/inicio.css') }}">
    <link rel="stylesheet" href="{{ asset('css/registro.css') }}">
    <style>
        /* Estilos para fortaleza de contraseña */
        .password-strength span {
            font-size: 0.875rem;
            font-weight: 500;
            padding: 2px 8px;
            border-radius: 4px;
        }
        .strength-weak {
            color: #dc3545;
            background-color: #f8d7da;
        }
        .strength-medium {
            color: #ffc107;
            background-color: #fff3cd;
        }
        .strength-strong {
            color: #28a745;
            background-color: #d4edda;
        }
        .error-message {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 5px;
            display: block;
        }
        .alert {
            padding: 12px;
            margin: 15px 0;
            border-radius: 5px;
        }
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
    </style>
    <script>
        // Función para verificar fortaleza de contraseña
        function checkPasswordStrength(password) {
            let strength = 0;
            
            // Longitud mínima
            if (password.length >= 8) strength++;
            
            // Contiene letras mayúsculas y minúsculas
            if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
            
            // Contiene números
            if (/[0-9]/.test(password)) strength++;
            
            // Contiene caracteres especiales
            if (/[^a-zA-Z0-9]/.test(password)) strength++;
            
            return strength;
        }

        // Función para validar contraseña en tiempo real
        function validatePassword() {
            const password = document.getElementById('Contrasena').value;
            const confirmPassword = document.getElementById('Contrasena_confirmation').value;
            const strengthElement = document.getElementById('password-strength');
            const strength = checkPasswordStrength(password);
            
            let strengthText = '';
            let strengthClass = '';
            
            if (strength === 0) {
                strengthText = 'Muy débil';
                strengthClass = 'strength-weak';
            } else if (strength <= 2) {
                strengthText = 'Débil';
                strengthClass = 'strength-weak';
            } else if (strength === 3) {
                strengthText = 'Media';
                strengthClass = 'strength-medium';
            } else {
                strengthText = 'Fuerte';
                strengthClass = 'strength-strong';
            }
            
            if (password.length > 0) {
                strengthElement.innerHTML = `<span class="${strengthClass}">Fortaleza: ${strengthText}</span>`;
            } else {
                strengthElement.innerHTML = '';
            }
            
            // Validar coincidencia de contraseñas
            const confirmField = document.getElementById('Contrasena_confirmation');
            if (confirmPassword.length > 0 && password !== confirmPassword) {
                confirmField.style.borderColor = '#dc3545';
            } else if (confirmPassword.length > 0 && password === confirmPassword) {
                confirmField.style.borderColor = '#28a745';
            } else {
                confirmField.style.borderColor = '#ddd';
            }
        }

        // Función para validar formulario antes de enviar
        function validateForm() {
            const nombre = document.getElementById('Nombre').value;
            const email = document.getElementById('Gmail').value;
            const telefono = document.getElementById('Telefono').value;
            const password = document.getElementById('Contrasena').value;
            const confirmPassword = document.getElementById('Contrasena_confirmation').value;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            const phoneRegex = /^[\d\s\-\+\(\)]{10,20}$/;
            
            let isValid = true;
            let errorMessage = '';
            
            // Validar nombre (mínimo 2 caracteres)
            if (nombre.trim().length < 2) {
                isValid = false;
                errorMessage += 'El nombre debe tener al menos 2 caracteres.\n';
                document.getElementById('Nombre').style.borderColor = '#dc3545';
            } else {
                document.getElementById('Nombre').style.borderColor = '#ddd';
            }
            
            // Validar email
            if (!emailRegex.test(email)) {
                isValid = false;
                errorMessage += 'Por favor, ingrese un email válido.\n';
                document.getElementById('Gmail').style.borderColor = '#dc3545';
            } else {
                document.getElementById('Gmail').style.borderColor = '#ddd';
            }
            
            // Validar teléfono
            if (!phoneRegex.test(telefono)) {
                isValid = false;
                errorMessage += 'Por favor, ingrese un teléfono válido (10-20 dígitos).\n';
                document.getElementById('Telefono').style.borderColor = '#dc3545';
            } else {
                document.getElementById('Telefono').style.borderColor = '#ddd';
            }
            
            // Validar contraseña (mínimo 6 caracteres según tu migración)
            if (password.length < 6) {
                isValid = false;
                errorMessage += 'La contraseña debe tener al menos 6 caracteres.\n';
                document.getElementById('Contrasena').style.borderColor = '#dc3545';
            } else {
                document.getElementById('Contrasena').style.borderColor = '#ddd';
            }
            
            // Validar coincidencia de contraseñas
            if (password !== confirmPassword) {
                isValid = false;
                errorMessage += 'Las contraseñas no coinciden.\n';
                document.getElementById('Contrasena_confirmation').style.borderColor = '#dc3545';
            } else if (confirmPassword.length > 0) {
                document.getElementById('Contrasena_confirmation').style.borderColor = '#28a745';
            }
            
            if (!isValid) {
                alert('Por favor, corrija los siguientes errores:\n\n' + errorMessage);
                return false;
            }
            
            return true;
        }

        // Inicializar validación
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('Contrasena').addEventListener('input', validatePassword);
            document.getElementById('Contrasena_confirmation').addEventListener('input', validatePassword);
            
            // Validar teléfono en tiempo real
            document.getElementById('Telefono').addEventListener('input', function(e) {
                this.value = this.value.replace(/[^\d\s\-\+\(\)]/g, '');
            });
        });
    </script>
</head>
<body>
    <header class="navbar">
        <div class="logo">
            <a href="{{ route('inicio') }}">
                <img src="{{ asset('images/Logo.jpg') }}" alt="Logo ManHeRu">
            </a>
            <span class="nombre">ManHeRu</span>
        </div>
    </header>

    <main class="registro-container">
        <div class="registro-card">
            <h2>Registrarme</h2>

            <!-- Mostrar mensajes de error -->
            @if ($errors->any())
                <div class="alert alert-error">
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Mostrar mensaje de éxito -->
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Mostrar mensaje de error específico -->
            @if (session('error'))
                <div class="alert alert-error">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST" onsubmit="return validateForm()">
                @csrf
                
                <!-- Campo Nombre (Nombre en DB) -->
                <div class="form-group">
                    <label for="Nombre">Nombre completo *</label>
                    <input type="text" id="Nombre" name="Nombre" 
                           placeholder="Ingresa tu nombre completo" 
                           value="{{ old('Nombre') }}" 
                           required
                           autocomplete="name"
                           autofocus>
                    @error('Nombre')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Campo Email (Gmail en DB) -->
                <div class="form-group">
                    <label for="Gmail">Correo electrónico (Gmail) *</label>
                    <input type="email" id="Gmail" name="Gmail" 
                           placeholder="ejemplo@gmail.com" 
                           value="{{ old('Gmail') }}" 
                           required
                           autocomplete="email">
                    @error('Gmail')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Campo Teléfono -->
                <div class="form-group">
                    <label for="Telefono">Teléfono *</label>
                    <input type="tel" id="Telefono" name="Telefono" 
                           placeholder="Ej: 555-123-4567 o +52 555 123 4567" 
                           value="{{ old('Telefono') }}" 
                           required
                           autocomplete="tel">
                    @error('Telefono')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Campo Contraseña -->
                <div class="form-group">
                    <label for="Contrasena">Contraseña *</label>
                    <input type="password" id="Contrasena" name="Contrasena" 
                           placeholder="6+ caracteres con mayúsculas, números y símbolos" 
                           required
                           autocomplete="new-password">
                    <div id="password-strength" class="password-strength"></div>
                    @error('Contrasena')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Campo Confirmar Contraseña -->
                <div class="form-group">
                    <label for="Contrasena_confirmation">Confirmar Contraseña *</label>
                    <input type="password" id="Contrasena_confirmation" 
                           name="Contrasena_confirmation" 
                           placeholder="Repite tu contraseña" 
                           required
                           autocomplete="new-password">
                </div>

                <!-- Campo Rol oculto (valor por defecto) -->
                <input type="hidden" name="ID_Rol" value="2">

                <button type="submit" class="btn-register">Registrarme</button>
                
                <p class="terms-notice">
                    Al registrarte, aceptas nuestros 
                    <br><a href="#">Términos de Servicio</a> y 
                    <a href="#">Política de Privacidad</a>.
                </p>
            </form>

            <div class="extra-options">
                <a href="{{ route('login') }}">¿Ya tienes una cuenta? Iniciar sesión</a>
                <a href="{{ url('/') }}">Volver al inicio</a>
            </div>
        </div>
    </main>
</body>
</html>