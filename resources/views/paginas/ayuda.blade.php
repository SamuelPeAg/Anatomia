
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ayuda — Anatomía MEDAC</title>
    @vite(['resources/css/principal.css', 'resources/css/paginas.css'])
</head>
<body>
    <x-header />

    <main class="estatico-container">
        <div class="estatico-card">
            <h1 class="estatico-titulo">Centro de Ayuda</h1>
            <p class="estatico-subtitulo">Estamos aquí para ayudarte a gestionar tus informes de forma eficiente.</p>

            <div class="estatico-seccion">
                <h3>Preguntas Frecuentes</h3>
                <p>Encuentra respuestas rápidas a las dudas más comunes sobre el uso de la plataforma.</p>
                <ul>
                    <li>¿Cómo registro un nuevo informe?</li>
                    <li>¿Dónde puedo ver el historial de pacientes?</li>
                    <li>¿Cómo descargo un informe finalizado?</li>
                </ul>
            </div>

            <div class="estatico-seccion">
                <h3>Soporte Técnico</h3>
                <p>Si experimentas problemas técnicos, contacta con nuestro equipo de soporte enviando un correo a: <strong>soporte@anatomiamedac.es</strong></p>
            </div>
        </div>
    </main>

    <x-footer />
</body>
</html>
