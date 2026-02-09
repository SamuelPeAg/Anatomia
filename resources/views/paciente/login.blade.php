<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Pacientes — Anatomía MEDAC</title>
    @vite(['resources/css/principal.css', 'resources/css/alerts.css'])
    <style>
        .login-body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);
            margin: 0;
            font-family: 'Inter', sans-serif;
        }
        .login-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 2.5rem;
            border-radius: 20px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            text-align: center;
        }
        .login-header h1 {
            color: #f8fafc;
            font-size: 1.875rem;
            margin-bottom: 0.5rem;
        }
        .login-header p {
            color: #94a3b8;
            margin-bottom: 2rem;
        }
        .login-form .campo {
            text-align: left;
            margin-bottom: 1.5rem;
        }
        .login-form label {
            display: block;
            color: #e2e8f0;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
        }
        .login-form input {
            width: 100%;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
            padding: 0.75rem 1rem;
            border-radius: 10px;
            transition: all 0.2s;
        }
        .login-form input:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.2);
        }
        .boton-login {
            width: 100%;
            background: #6366f1;
            color: white;
            border: none;
            padding: 0.75rem;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }
        .boton-login:hover {
            background: #4f46e5;
        }
        .footer-links {
            margin-top: 1.5rem;
            font-size: 0.875rem;
        }
        .footer-links a {
            color: #94a3b8;
            text-decoration: none;
        }
        .footer-links a:hover {
            color: #f8fafc;
        }
    </style>
</head>
<body class="login-body">
    <div class="login-card">
        <header class="login-header">
            <h1>Acceso Pacientes</h1>
            <p>Consulte sus resultados médicos de forma segura.</p>
        </header>

        @if($errors->any())
            <div class="alert alert-danger" style="margin-bottom: 1.5rem; font-size: 0.875rem; text-align: left;">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger" style="margin-bottom: 1.5rem; font-size: 0.875rem; text-align: left;">
                {{ session('error') }}
            </div>
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
