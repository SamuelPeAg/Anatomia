<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Pacientes — Anatomía MEDAC</title>
    @vite(['resources/css/principal.css', 'resources/css/alerts.css', 'resources/css/paciente-login.css'])

</head>
<body class="login-body">
    <div class="login-card">
        <header class="login-header">
            <h1>Acceso Pacientes</h1>
            <p>Consulte sus resultados médicos de forma segura.</p>
        </header>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if($errors->any() || session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                let msg = '';
                @foreach($errors->all() as $error) msg += '• {{ $error }}\n'; @endforeach
                @if(session('error')) msg += '{{ session("error") }}'; @endif

                Swal.fire({
                    icon: 'error',
                    title: 'Acceso Denegado',
                    text: msg,
                    confirmButtonColor: '#0234AB'
                });
            });
        </script>
    @endif

        <form action="{{ route('paciente.login') }}" method="POST" class="login-form">
            @csrf
            <div class="campo">
                <label for="email">Correo electrónico de su expediente</label>
                <input type="email" id="email" name="email" required placeholder="ejemplo@correo.com" value="{{ old('email') }}">
            </div>
            <button type="submit" class="boton-login">Ver mis informes</button>
        </form>

        <div class="footer-links">
            <a href="{{ route('inicio') }}">Volver al portal principal</a>
        </div>
    </div>
</body>
</html>
