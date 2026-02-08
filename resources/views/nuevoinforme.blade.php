<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Nuevo informe — DAVANTE</title>

    @vite(['resources/css/nuevoinforme.css', 'resources/css/principal.css'])
  </head>

  <body>
    <x-header />

    <main class="pagina">
      <section class="contenedor">
        <!-- CABECERA DE PÁGINA -->
        <header class="cabecera-pagina">
          <div class="cabecera-izquierda">
            <h1 class="titulo-pagina">Nuevo informe</h1>
            <p class="subtitulo-pagina">
              El informe se completa por fases:
              <strong>Recepción → Procesamiento → Tinción → Citodiagnóstico</strong>.
            </p>
          </div>

          <div class="cabecera-derecha">
            <span class="etiqueta etiqueta-aviso" id="etiquetaEstado">Incompleto</span>
          </div>
        </header>

        <!-- PASOS -->
        <nav class="pasos" aria-label="Progreso del informe">
          <button class="paso paso-activo" type="button" data-paso="1">
            <span class="paso-numero">1</span>
            <span class="paso-texto">Recepción</span>
          </button>

          <button class="paso" type="button" data-paso="2">
            <span class="paso-numero">2</span>
            <span class="paso-texto">Procesamiento</span>
          </button>

          <button class="paso" type="button" data-paso="3">
            <span class="paso-numero">3</span>
            <span class="paso-texto">Tinción</span>
          </button>

          <button class="paso" type="button" data-paso="4">
            <span class="paso-numero">4</span>
            <span class="paso-texto">Citodiagnóstico</span>
          </button>
        </nav>

        <!-- TARJETA -->
        <article class="tarjeta">
          <div class="tarjeta-cabecera">
            <h2 class="tarjeta-titulo" id="tituloFase">Fase 1 — Recepción</h2>
            <p class="tarjeta-ayuda">
              Campos con <span class="obligatorio">*</span> obligatorios.
            </p>
          </div>

          <div class="tarjeta-cuerpo">
            <form class="formulario" 
            name="formularioFase1" 
            action="{{ route("guardar_informe") }}" 
            method="POST"
            id="formularioNuevoInforme" 
            enctype="multipart/form-data"
            >
            @csrf
              <!-- Fases del informe (Componentes Blade) -->
              <x-fase-recepcion />
              <x-fase-procesamiento />
              <x-fase-tincion />
              <x-fase-citodiagnostico />
            </form>
          </div>
        </article>
      </section>
    </main>

    <x-footer />
    @vite(['resources/js/formulario.js'])
  </body>
</html>
