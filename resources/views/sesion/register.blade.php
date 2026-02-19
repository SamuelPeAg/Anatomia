<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Usuario - Davante</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Estilos y Scripts con Vite -->
    @vite(['resources/css/principal.css', 'resources/css/login.css', 'resources/js/alertas.js'])

    <!-- Iconos Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>

<body data-error="{{ $errors->any() ? implode('\n', $errors->all()) : '' }}" data-error-title="Error en el registro">
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
            <a href="{{ route('login') }}" class="btn-premium">Iniciar Sesión</a>
        </nav>
    </header>

    <div class="auth-container">
        <div class="login-wrapper login-wrapper-auto">
            <h2>Crear Usuario</h2>

            <form action="{{ route('users.store') }}" method="POST">
                @csrf

                {{-- NAME --}}
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-person"></i>
                    </span>
                    <input type="text" name="name" class="form-control"
                        placeholder="Nombre completo" value="{{ old('name') }}" maxlength="70" required>
                </div>

                {{-- EMAIL --}}
                <div class="input-group mt-3">
                    <span class="input-group-text">
                        <i class="bi bi-envelope"></i>
                    </span>
                    <input type="email" name="email" class="form-control"
                        placeholder="ejemplo@alu.medac.es" value="{{ old('email') }}" maxlength="70" required>
                </div>
                <div class="form-text text-muted mb-3" style="font-size: 0.8rem;">
                    El correo electrónico debe ser corporativo de MEDAC (@alu.medac.es o @doc.medac.es)
                </div>

                {{-- PASSWORD --}}
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-key"></i>
                    </span>
                    <input type="password" name="password" class="form-control"
                        placeholder="Contraseña (mínimo 6 caracteres)" minlength="6" required>
                </div>

                {{-- CONFIRM PASSWORD --}}
                <div class="input-group mt-3">
                    <span class="input-group-text">
                        <i class="bi bi-shield-check"></i>
                    </span>
                    <input type="password" name="password_confirmation" class="form-control"
                        placeholder="Confirmar contraseña" minlength="6" required>
                </div>

                <button class="btn-login mt-4">CREAR USUARIO</button>
            </form>

            <div class="login-footer">
                <a href="{{ route('login') }}">¿Ya tienes cuenta? Entra aquí</a>
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
