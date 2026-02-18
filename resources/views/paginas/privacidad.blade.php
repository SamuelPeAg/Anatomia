
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacidad — Anatomía MEDAC</title>
    @vite(['resources/css/principal.css', 'resources/css/paginas.css'])
</head>
<body>
    <x-header />

    <main class="estatico-container">
        <div class="estatico-card">
            <h1 class="estatico-titulo">Política de Privacidad</h1>
            <p class="estatico-subtitulo">Tu privacidad y la de tus pacientes es nuestra prioridad técnica.</p>

            <div class="estatico-seccion">
                <h3>Tratamiento de Datos</h3>
                <p>En cumplimiento con el RGPD, informamos que los datos recogidos en esta plataforma (nombres de pacientes, correos y hallazgos médicos) son tratados exclusivamente para la gestión de informes patológicos.</p>
            </div>

            <div class="estatico-seccion">
                <h3>Seguridad</h3>
                <p>Implementamos medidas de seguridad de última generación, incluyendo cifrado de datos y acceso restringido mediante autenticación avanzada para proteger la información sensible.</p>
            </div>

            <div class="estatico-seccion">
                <h3>Derechos de Acceso</h3>
                <p>Los usuarios pueden solicitar el acceso, rectificación o supresión de sus datos personales en cualquier momento contactando con el administrador del sistema.</p>
            </div>
        </div>
    </main>

    <x-footer />
</body>
</html>
