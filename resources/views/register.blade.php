<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Usuario - Davante</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- CSS personalizado -->
    <link rel="stylesheet" href="./css/login.css">

    <!-- Iconos Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>

<body>

    <div class="login-wrapper">

        <h2>Crear Usuario</h2>

        <form action="{{ route('users.store') }}" method="POST">
            @csrf

            {{-- NAME --}}
            <div class="input-group">
                <span class="input-group-text">
                    <i class="bi bi-person"></i>
                </span>
                <input type="text" name="name" class="form-control"
                    placeholder="Nombre completo" value="{{ old('name') }}" required>
            </div>
            @error('name')
                <div class="alert alert-danger p-1">{{ $message }}</div>
            @enderror

            {{-- EMAIL --}}
            <div class="input-group">
                <span class="input-group-text">
                    <i class="bi bi-envelope"></i>
                </span>
                <input type="email" name="email" class="form-control"
                    placeholder="Correo electrónico" value="{{ old('email') }}" required>
            </div>
            @error('email')
                <div class="alert alert-danger p-1">{{ $message }}</div>
            @enderror

            {{-- PASSWORD --}}
            <div class="input-group">
                <span class="input-group-text">
                    <i class="bi bi-key"></i>
                </span>
                <input type="password" name="password" class="form-control"
                    placeholder="Contraseña" required>
            </div>
            @error('password')
                <div class="alert alert-danger p-1">{{ $message }}</div>
            @enderror

            {{-- CONFIRM PASSWORD --}}
            <div class="input-group">
                <span class="input-group-text">
                    <i class="bi bi-shield-check"></i>
                </span>
                <input type="password" name="password_confirmation" class="form-control"
                    placeholder="Confirmar contraseña" required>
            </div>

            <button class="btn-login mt-3">CREAR USUARIO</button>

        </form>

        <!-- Footer -->
        <div class="login-footer">
            <a href="{{ route('home') }}">Volver al inicio de sesión</a>
        </div>
    </div>

</body>

</html>
