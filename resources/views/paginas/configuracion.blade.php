
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuración — Anatomía MEDAC</title>
    @vite(['resources/css/principal.css', 'resources/css/paginas.css'])
</head>
<body>
    <x-header />

    <main class="estatico-container">
        <div class="estatico-card">
            <h1 class="estatico-titulo">Configuración</h1>
            <p class="estatico-subtitulo">Ajusta los parámetros generales de la plataforma y tu perfil.</p>

            <div class="estatico-seccion">
                <h3>Preferencias de Perfil</h3>
                <p>Configura tu nombre de usuario, contraseña y foto de perfil corporativa.</p>
            </div>

            <div class="estatico-seccion">
                <h3>Ajustes del Laboratorio</h3>
                <p>Personaliza los prefijos de los códigos identificadores y los tipos de muestras por defecto del sistema.</p>
            </div>

            <div class="estatico-seccion">
                <h3>Notificaciones</h3>
                <p>Gestiona los avisos por correo electrónico para informes pendientes de revisión o validados.</p>
            </div>
            
            <p style="margin-top: 2rem; font-size: 0.9rem; color: #94a3b8; font-style: italic;">Nota: Algunas configuraciones avanzadas solo están disponibles para el perfil de Administrador.</p>
        </div>
    </main>

    <x-footer />
</body>
</html>
