<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Pacientes — Anatomía MEDAC</title>
    @vite(['resources/css/principal.css', 'resources/css/alerts.css', 'resources/css/paciente-login.css', 'resources/js/alertas.js'])

</head>
<body class="login-body" 
      data-error="{{ ($errors->any() || session('error')) ? implode('\n', $errors->all()) . session('error') : '' }}" 
      data-error-title="Acceso Denegado">
    <div class="login-card">
        <header class="login-header">
            <h1>Acceso Pacientes</h1>
            <p>Consulte sus resultados médicos de forma segura.</p>
        </header>


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
