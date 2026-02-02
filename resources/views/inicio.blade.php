<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Inicio - Anatomía MEDAC</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

        <!-- Estilos vinculados externamente -->
        <link rel="stylesheet" href="{{ asset('css/principal.css') }}">
        <script src="{{ asset('js/principal.js') }}" defer></script>
    </head>
    <body>
        <x-header />

        <main class="main-content">
            <section class="hero-section">
                <h1 class="hero-title">Optimiza tus prácticas de Anatomía Patológica</h1>
                <p class="hero-subtitle">
                    Dile adiós al caos de los correos y las imágenes perdidas. 
                    Una plataforma centralizada para el seguimiento paso a paso del procesado de muestras.
                </p>
                <div class="cta-group">
                    <a href="{{ route('login') }}" class="btn btn-register">
                        Empieza ahora
                    </a>
                </div>
            </section>

            <section class="features">
                <div class="feature-card">
                    <h3>Procesado por Etapas</h3>
                    <p>Registra cada fase del procesado de la muestra de forma estructurada y cronológica.</p>
                </div>
                <div class="feature-card">
                    <h3>Gestión de Imágenes</h3>
                    <p>Sube y organiza las fotografías de tus prácticas directamente en la plataforma, sin intermediarios.</p>
                </div>
                <div class="feature-card">
                    <h3>Adiós al Papeleo</h3>
                    <p>Informes digitales listos para revisión, eliminando la necesidad de formularios por correo.</p>
                </div>
            </section>
        </main>

        <x-footer />
    </body>
</html>
