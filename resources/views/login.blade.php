<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Davante Login</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- CSS personalizado -->
    <link rel="stylesheet" href="./css/login.css">

    <!-- Iconos Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>

<body>
    <div class="login-wrapper">

        <h2>Iniciar Sesión</h2>

        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="input-group">
                <span class="input-group-text">
                    <i class="bi bi-person"></i>
                </span>
                <input type="text" name="name" class="form-control" placeholder="ingrese el nombre" required>
            </div>

            <div class="input-group">
                <span class="input-group-text">
                    <i class="bi bi-key"></i>
                </span>
                <input type="password" name="password" class="form-control" placeholder="ingrese la contraseña"
                    required>
            </div>

            <!-- <button class="btn-google">
                <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" alt="Google">
                Iniciar sesión con Google
            </button> -->


            <button class="btn-login mt-3">INICIAR SESIÓN</button>

        </form>
        @if ($errors->has('login'))
            <div class="alert alert-danger mt-3">
                {{ $errors->first('login') }}
            </div>
        @endif


        <!-- Enlace footer -->
        <div class="login-footer">
            <a href="#">¿Has olvidado tu contraseña?</a>
        </div>
    </div>

</body>

</html>
