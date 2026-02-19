<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Anatomía MEDAC — Gestión Patológica Premium</title>

        <!-- Estilos y Scripts con Vite -->
        @vite(['resources/css/principal.css', 'resources/css/dashboard.css', 'resources/js/principal.js'])
    </head>
    <body>
        <x-header />

        <main>
            <!-- Hero Section -->
            <section class="hero">
                <div class="hero-tag">Laboratorio Patológico Digital</div>
                <h1 class="hero-title">Gestión centralizada para Anatomía Patológica</h1>
                <p class="hero-subtitle">
                    Optimiza el procesado de muestras y la generación de informes técnicos con una plataforma diseñada para laboratorios modernos.
                </p>
                <div class="hero-btns">
                    @auth
                        <a href="{{ route('nuevo informe') }}" class="btn-premium">Nuevo Informe</a>
                        <a href="{{ route('revision') }}" class="btn-outline">Informes</a>
                    @else
                        <a href="{{ route('login') }}" class="btn-premium">Comenzar Ahora</a>
                        <a href="#proceso" class="btn-outline">Ver Funcionamiento</a>
                    @endauth
                </div>
            </section>

            <!-- Benefits/Process Section -->
            <section class="benefits-section" id="proceso">
                <div class="section-header">
                    <h2 class="section-title">¿Por qué elegir nuestra plataforma?</h2>
                    <p class="section-desc">Diseñamos cada función pensando en la precisión y rapidez que requiere un entorno de laboratorio.</p>
                </div>

                <div class="benefits-grid">
                    <div class="benefit-list">
                        <div class="benefit-item">
                            <div class="benefit-num">1</div>
                            <div class="benefit-content">
                                <h4>Control Total del Proceso</h4>
                                <p>Supervisión detallada de cada fase: recepción, procesamiento, tinción y citodiagnóstico.</p>
                            </div>
                        </div>
                        <div class="benefit-item">
                            <div class="benefit-num">2</div>
                            <div class="benefit-content">
                                <h4>Gestión de Imágenes</h4>
                                <p>Captura y almacenamiento seguro de fotografías microscópicas organizadas por expediente.</p>
                            </div>
                        </div>
                        <div class="benefit-item">
                            <div class="benefit-num">3</div>
                            <div class="benefit-content">
                                <h4>Informes Profesionales</h4>
                                <p>Generación automática de documentos técnicos listos para su revisión y envío.</p>
                            </div>
                        </div>
                    </div>
                    <div class="benefit-visual">
                        <div class="visual-placeholder">
                            <img src="{{ asset('images/inicio.png') }}" alt="Gestión de Informes" style="width: 100%; height: 100%; object-fit: cover; border-radius: 12px;">
                        </div>
                    </div>
                </div>
            </section>

            <!-- Features Grid -->
            <section class="features-container">
                <div class="features-grid">
                    <article class="feature-card">
                        <h3>Trazabilidad Completa</h3>
                        <p>Historial inalterable de cada muestra, asegurando el cumplimiento de normativas de calidad.</p>
                    </article>

                    <article class="feature-card">
                        <h3>Acceso Remoto</h3>
                        <p>Consulta expedientes e imágenes desde cualquier dispositivo autorizado con total seguridad.</p>
                    </article>

                    <article class="feature-card">
                        <h3>Búsqueda Avanzada</h3>
                        <p>Localiza cualquier informe o muestra en segundos mediante filtros inteligentes y etiquetas.</p>
                    </article>
                </div>
            </section>
        </main>

        <x-footer />
    </body>
</html>
