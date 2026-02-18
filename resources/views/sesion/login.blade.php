<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Davante Login</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Estilos y Scripts con Vite -->
    @vite(['resources/css/principal.css', 'resources/css/login.css'])

    <!-- Iconos Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>

<body>
    <!-- Header Integrado -->
    <header class="main-header">
        <a href="{{ url('/') }}" class="brand">
            <svg class="logo-icon" width="32" height="32" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect width="40" height="40" rx="8" fill="#0234AB"/>
                <path d="M20 10L28 15V25L20 30L12 25V15L20 10Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <circle cx="20" cy="20" r="3" fill="white"/>
            </svg>
            <span class="brand-name">Anatomía MEDAC</span>
        </a>
        <nav class="nav-actions">
            {{-- <a href="{{ url('/') }}" class="btn-ghost">Volver Atrás</a> --}}
            @if (Route::has('register'))
                <a href="{{ route('register') }}" class="btn-premium">Registro</a>
            @endif
        </nav>
    </header>

    <div class="auth-container">
        <div class="login-wrapper">

            <h2>Iniciar Sesión</h2>

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-envelope"></i>
                    </span>
                    <input type="email" name="email" class="form-control" placeholder="Ingrese el correo" maxlength="70" required>
                </div>

                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-key"></i>
                    </span>
                    <input type="password" name="password" class="form-control" placeholder="Ingrese la contraseña"
                        required>
                </div>

                <button class="btn-login mt-3">INICIAR SESIÓN</button>
            </form>

            @if ($errors->has('login'))
                <div class="alert alert-danger mt-3">
                    {{ $errors->first('login') }}
                </div>
            @endif

            <div class="login-footer">
                @if (Route::has('register'))
                    <span>¿No tienes cuenta? <a href="{{ route('register') }}">Regístrate aquí</a></span>
                @endif
            </div>
        </div>
    </div>

    <!-- Footer Integrado -->
    <footer class="main-footer">
        <nav class="footer-nav">
            <a href="{{ url('/') }}">Inicio</a>
            <a href="#">Ayuda</a>
            <a href="#">Privacidad</a>
            <a href="#">Términos</a>
        </nav>
        <div class="copy">
            &copy; {{ date('Y') }} Anatomía MEDAC. Todos los derechos reservados.
        </div>
    </footer>
</body>

</html>
