<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ManHeRu - Iniciar sesión</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <style>
        .error-message {
            color: #e74c3c;
            font-size: 0.875rem;
            margin-top: 5px;
            display: block;
        }
        .alert {
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            text-align: center;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
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

    <main class="login-container">
        <div class="login-card">
            <h2>Iniciar Sesión</h2>
            <p class="subtitle">Accede a tu cuenta para continuar</p>

            <!-- Mostrar mensajes de éxito -->
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Mostrar errores generales -->
            @if($errors->any())
                <div class="alert alert-error">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf 
                
                <!-- Campo email (se mapeará a Gmail) -->
                <div class="form-group">
                    <label for="email">Correo electrónico (Gmail)</label>
                    <input type="email" id="email" name="email" 
                           value="{{ old('email') }}" 
                           placeholder="ejemplo@gmail.com" required>
                    @error('email')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Campo contraseña -->
                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" 
                           placeholder="••••••••" required>
                    @error('password')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Recordar sesión (opcional) -->
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        Recordar sesión
                    </label>
                </div>

                <button type="submit" class="btn-login">Entrar</button>
            </form>

            <div class="extra-options">
                <a href="#">¿Olvidaste tu contraseña?</a>
                <a href="{{ route('register.form') }}">Registrarme</a>
            </div>
        </div>
    </main>
</body>
</html>