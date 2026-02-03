<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Nuevo informe — DAVANTE</title>

    <link rel="stylesheet" href="{{ asset('css/nuevoinforme.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/header_footer.css') }}" />
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
            >
            @csrf
              <!-- =======================================================
                   FASE 1 — RECEPCIÓN
              ======================================================== -->
              <section class="fase fase-activa" id="fase-1" data-fase="1">
                <div class="rejilla">
                  <div class="campo">
                    <label class="etiqueta-campo" for="tipo_muestra">
                      Tipo de muestra <span class="obligatorio">*</span>
                    </label>
                    <select class="control-campo" id="tipo_muestra" name="tipo_muestra" required>
                      <option value="">Selecciona un tipo</option>
                      <option value="B">Biopsia</option>
                      <option value="E">Esputo</option>
                      <option value="CB">Cavidad bucal</option>
                      <option value="CV">Citología vaginal</option>
                      <option value="EX">Extensión sanguínea</option>
                      <option value="O">Orinas</option>
                      <option value="ES">Semen</option>
                      <option value="I">Improntas</option>
                      <option value="F">Frotis</option>
                      <option value="OTRO">Otro</option>
                    </select>
                  </div>

                  <div class="campo">
                    <label class="etiqueta-campo" for="codigo_identificador">
                      Código identificador (auto)
                    </label>
                    <input
                      class="control-campo"
                      type="text"
                      id="codigo_identificador"
                      name="codigo_identificador"
                      placeholder="Ej: B2530"
                      readonly
                    />
                    <small class="ayuda-campo" id="ayudaCodigo">
                      Se genera automáticamente al seleccionar el tipo.
                    </small>
                  </div>
                </div>

                <div class="campo">
                  <label class="etiqueta-campo" for="observaciones_llegada">
                    Observaciones de la llegada <span class="obligatorio">*</span>
                  </label>
                  <textarea
                    class="control-campo"
                    id="observaciones_llegada"
                    name="observaciones_llegada"
                    rows="5"
                    placeholder="Estado del recipiente, incidencias, cantidad, etc."
                    required
                  ></textarea>
                </div>

                <div class="campo oculto" id="campoOrgano">
                  <label class="etiqueta-campo" for="organo">
                    Órgano (solo biopsia) <span class="obligatorio">*</span>
                  </label>
                  <input class="control-campo" type="text" id="organo" name="organo" />
                </div>

                <!-- IMÁGENES RECEPCIÓN (opcionales, añadir/quitar) -->
                <div class="subtarjeta">
                  <div class="subtarjeta-cabecera">
                    <h3 class="subtarjeta-titulo">Imágenes de recepción (opcional)</h3>
                    <p class="subtarjeta-ayuda">Puedes adjuntar 0 o más imágenes.</p>
                  </div>

                  <div class="subtarjeta-cuerpo">
                    <div class="lista-imagenes" data-lista-imagenes="recepcion">
                      <div class="fila-imagen">
                        <div class="archivo-imagen">
                          <label class="etiqueta-campo">Imagen</label>
                          <input class="control-campo" type="file" name="recepcion_img[]" accept="image/*" />
                        </div>

                        <div class="descripcion-imagen">
                          <label class="etiqueta-campo">Descripción (opcional)</label>
                          <input class="control-campo" type="text" name="recepcion_desc[]" placeholder="Descripción..." />
                        </div>

                        <div class="acciones-imagen">
                          <button class="boton boton-peligro" type="button" data-eliminar-fila>
                            Eliminar
                          </button>
                        </div>
                      </div>
                    </div>

                    <div class="acciones-imagenes">
                      <button class="boton boton-secundario" type="button" data-anadir-fila="recepcion">
                        Añadir imagen
                      </button>
                    </div>

                    <template id="plantilla-recepcion">
                      <div class="fila-imagen">
                        <div class="archivo-imagen">
                          <label class="etiqueta-campo">Imagen</label>
                          <input class="control-campo" type="file" name="recepcion_img[]" accept="image/*" />
                        </div>

                        <div class="descripcion-imagen">
                          <label class="etiqueta-campo">Descripción (opcional)</label>
                          <input class="control-campo" type="text" name="recepcion_desc[]" placeholder="Descripción..." />
                        </div>

                        <div class="acciones-imagen">
                          <button class="boton boton-peligro" type="button" data-eliminar-fila>
                            Eliminar
                          </button>
                        </div>
                      </div>
                    </template>
                  </div>
                </div>

                <div class="acciones">
                  <div class="acciones-izquierda">
                    <a class="enlace" href="#">Volver</a>
                  </div>

                  <div class="acciones-derecha">
                    <button class="boton boton-secundario" type="submit" id="botonGuardarFase1">
                      Guardar recepción (incompleto)
                    </button>
                    <button class="boton boton-principal" type="button" id="botonIrFase2">
                      Siguiente fase
                    </button>
                  </div>
                </div>
              </section>

              <!-- =======================================================
                   FASE 2 — PROCESAMIENTO
              ======================================================== -->
              <section class="fase" id="fase-2" data-fase="2">
                <div class="campo">
                  <label class="etiqueta-campo" for="tipo_procesamiento">
                    Tipo de procesamiento <span class="obligatorio">*</span>
                  </label>
                  <select class="control-campo" id="tipo_procesamiento" name="tipo_procesamiento" required>
                    <option value="">Selecciona…</option>
                    <option value="CITOCENTRIFUGADO">Citocentrifugado</option>
                    <option value="EXTENSION">Extensión / Frotis</option>
                    <option value="BLOQUE_CELULAR">Bloque celular</option>
                    <option value="FILTRADO">Filtrado</option>
                    <option value="OTRO">Otro</option>
                  </select>
                </div>

                <div class="campo oculto" id="campoProcesamientoOtro">
                  <label class="etiqueta-campo" for="procesamiento_otro">
                    Especifica el procesamiento <span class="obligatorio">*</span>
                  </label>
                  <input class="control-campo" type="text" id="procesamiento_otro" name="procesamiento_otro" />
                </div>

                <div class="campo">
                  <label class="etiqueta-campo" for="observaciones_procesamiento">
                    Observaciones del procesamiento
                  </label>
                  <textarea
                    class="control-campo"
                    id="observaciones_procesamiento"
                    name="observaciones_procesamiento"
                    rows="5"
                    placeholder="Tiempo, técnica, incidencias, calidad de la preparación..."
                  ></textarea>
                </div>

                <!-- IMÁGENES PROCESAMIENTO -->
                <div class="subtarjeta">
                  <div class="subtarjeta-cabecera">
                    <h3 class="subtarjeta-titulo">Imágenes del procesamiento (opcional)</h3>
                    <p class="subtarjeta-ayuda">Puedes adjuntar 0 o más imágenes.</p>
                  </div>

                  <div class="subtarjeta-cuerpo">
                    <div class="lista-imagenes" data-lista-imagenes="procesamiento">
                      <div class="fila-imagen">
                        <div class="archivo-imagen">
                          <label class="etiqueta-campo">Imagen</label>
                          <input class="control-campo" type="file" name="procesamiento_img[]" accept="image/*" />
                        </div>

                        <div class="descripcion-imagen">
                          <label class="etiqueta-campo">Descripción (opcional)</label>
                          <input class="control-campo" type="text" name="procesamiento_desc[]" placeholder="Descripción..." />
                        </div>

                        <div class="acciones-imagen">
                          <button class="boton boton-peligro" type="button" data-eliminar-fila>
                            Eliminar
                          </button>
                        </div>
                      </div>
                    </div>

                    <div class="acciones-imagenes">
                      <button class="boton boton-secundario" type="button" data-anadir-fila="procesamiento">
                        Añadir imagen
                      </button>
                    </div>

                    <template id="plantilla-procesamiento">
                      <div class="fila-imagen">
                        <div class="archivo-imagen">
                          <label class="etiqueta-campo">Imagen</label>
                          <input class="control-campo" type="file" name="procesamiento_img[]" accept="image/*" />
                        </div>

                        <div class="descripcion-imagen">
                          <label class="etiqueta-campo">Descripción (opcional)</label>
                          <input class="control-campo" type="text" name="procesamiento_desc[]" placeholder="Descripción..." />
                        </div>

                        <div class="acciones-imagen">
                          <button class="boton boton-peligro" type="button" data-eliminar-fila>
                            Eliminar
                          </button>
                        </div>
                      </div>
                    </template>
                  </div>
                </div>

                <div class="acciones">
                  <div class="acciones-izquierda">
                    <button class="boton boton-fantasma" type="button" id="botonVolverFase1">Volver</button>
                  </div>

                  <div class="acciones-derecha">
                    <button class="boton boton-secundario" type="button" id="botonGuardarFase2">
                      Guardar procesamiento (incompleto)
                    </button>
                    <button class="boton boton-principal" type="button" id="botonIrFase3">
                      Siguiente fase
                    </button>
                  </div>
                </div>
              </section>

              <!-- =======================================================
                   FASE 3 — TINCIÓN
              ======================================================== -->
              <section class="fase" id="fase-3" data-fase="3">
                <div class="campo">
                  <label class="etiqueta-campo" for="tipo_tincion">
                    Tipo de tinción <span class="obligatorio">*</span>
                  </label>

                  <input
                    class="control-campo"
                    id="tipo_tincion"
                    name="tipo_tincion"
                    list="tinciones_sugeridas"
                    placeholder="Ej: Hematoxilina-Eosina, Giemsa, PAP..."
                    required
                  />

                  <datalist id="tinciones_sugeridas">
                    <option value="Hematoxilina - Eosina (H/E)"></option>
                    <option value="Giemsa"></option>
                    <option value="PAS"></option>
                    <option value="Papanicolaou (PAP)"></option>
                    <option value="Gram"></option>
                    <option value="Ziehl-Neelsen"></option>
                  </datalist>
                </div>

                <div class="campo">
                  <label class="etiqueta-campo" for="observacion_tincion">
                    Observaciones de la tinción <span class="obligatorio">*</span>
                  </label>
                  <textarea
                    class="control-campo"
                    id="observacion_tincion"
                    name="observacion_tincion"
                    rows="6"
                    placeholder="Qué se observa tras la tinción, calidad, hallazgos..."
                    required
                  ></textarea>
                </div>

                <!-- IMÁGENES TINCIÓN -->
                <div class="subtarjeta">
                  <div class="subtarjeta-cabecera">
                    <h3 class="subtarjeta-titulo">Imágenes de la tinción (opcional)</h3>
                    <p class="subtarjeta-ayuda">Puedes adjuntar 0 o más imágenes.</p>
                  </div>

                  <div class="subtarjeta-cuerpo">
                    <div class="lista-imagenes" data-lista-imagenes="tincion">
                      <div class="fila-imagen">
                        <div class="archivo-imagen">
                          <label class="etiqueta-campo">Imagen</label>
                          <input class="control-campo" type="file" name="tincion_img[]" accept="image/*" />
                        </div>

                        <div class="descripcion-imagen">
                          <label class="etiqueta-campo">Descripción (opcional)</label>
                          <input class="control-campo" type="text" name="tincion_desc[]" placeholder="Descripción..." />
                        </div>

                        <div class="acciones-imagen">
                          <button class="boton boton-peligro" type="button" data-eliminar-fila>
                            Eliminar
                          </button>
                        </div>
                      </div>
                    </div>

                    <div class="acciones-imagenes">
                      <button class="boton boton-secundario" type="button" data-anadir-fila="tincion">
                        Añadir imagen
                      </button>
                    </div>

                    <template id="plantilla-tincion">
                      <div class="fila-imagen">
                        <div class="archivo-imagen">
                          <label class="etiqueta-campo">Imagen</label>
                          <input class="control-campo" type="file" name="tincion_img[]" accept="image/*" />
                        </div>

                        <div class="descripcion-imagen">
                          <label class="etiqueta-campo">Descripción (opcional)</label>
                          <input class="control-campo" type="text" name="tincion_desc[]" placeholder="Descripción..." />
                        </div>

                        <div class="acciones-imagen">
                          <button class="boton boton-peligro" type="button" data-eliminar-fila>
                            Eliminar
                          </button>
                        </div>
                      </div>
                    </template>
                  </div>
                </div>

                <div class="acciones">
                  <div class="acciones-izquierda">
                    <button class="boton boton-fantasma" type="button" id="botonVolverFase2">Volver</button>
                  </div>

                  <div class="acciones-derecha">
                    <button class="boton boton-secundario" type="button" id="botonGuardarFase3">
                      Guardar tinción (incompleto)
                    </button>
                    <button class="boton boton-principal" type="button" id="botonIrFase4">
                      Siguiente fase
                    </button>
                  </div>
                </div>
              </section>

              <!-- =======================================================
                   FASE 4 — CITODIAGNÓSTICO
              ======================================================== -->
              <section class="fase" id="fase-4" data-fase="4">
                <div class="campo">
                  <label class="etiqueta-campo" for="citodiagnostico">
                    Citodiagnóstico <span class="obligatorio">*</span>
                  </label>
                  <textarea
                    class="control-campo"
                    id="citodiagnostico"
                    name="citodiagnostico"
                    rows="6"
                    placeholder="Descripción diagnóstica, hallazgos citológicos / tisulares..."
                    required
                  ></textarea>
                </div>

                <div class="subtarjeta">
                  <div class="subtarjeta-cabecera">
                    <h3 class="subtarjeta-titulo">Imágenes microscópicas obligatorias</h3>
                    <p class="subtarjeta-ayuda">Debes adjuntar 4 imágenes: x4, x10, x40 y x100.</p>
                  </div>

                  <div class="subtarjeta-cuerpo">
                    <div class="lista-imagenes" id="listaImagenesObligatorias">
                      <!-- x4 -->
                      <div class="fila-imagen fila-obligatoria">
                        <div class="archivo-imagen">
                          <label class="etiqueta-campo">Imagen x4 <span class="obligatorio">*</span></label>
                          <input class="control-campo" type="file" name="micros_required_img[x4]" accept="image/*" required />
                        </div>
                        <div class="zoom-imagen">
                          <label class="etiqueta-campo">Zoom</label>
                          <input class="control-campo" type="text" value="x4" readonly />
                        </div>
                        <div class="descripcion-imagen">
                          <label class="etiqueta-campo">Descripción (opcional)</label>
                          <input class="control-campo" type="text" name="micros_required_desc[x4]" placeholder="Qué se ve..." />
                        </div>
                      </div>

                      <!-- x10 -->
                      <div class="fila-imagen fila-obligatoria">
                        <div class="archivo-imagen">
                          <label class="etiqueta-campo">Imagen x10 <span class="obligatorio">*</span></label>
                          <input class="control-campo" type="file" name="micros_required_img[x10]" accept="image/*" required />
                        </div>
                        <div class="zoom-imagen">
                          <label class="etiqueta-campo">Zoom</label>
                          <input class="control-campo" type="text" value="x10" readonly />
                        </div>
                        <div class="descripcion-imagen">
                          <label class="etiqueta-campo">Descripción (opcional)</label>
                          <input class="control-campo" type="text" name="micros_required_desc[x10]" placeholder="Qué se ve..." />
                        </div>
                      </div>

                      <!-- x40 -->
                      <div class="fila-imagen fila-obligatoria">
                        <div class="archivo-imagen">
                          <label class="etiqueta-campo">Imagen x40 <span class="obligatorio">*</span></label>
                          <input class="control-campo" type="file" name="micros_required_img[x40]" accept="image/*" required />
                        </div>
                        <div class="zoom-imagen">
                          <label class="etiqueta-campo">Zoom</label>
                          <input class="control-campo" type="text" value="x40" readonly />
                        </div>
                        <div class="descripcion-imagen">
                          <label class="etiqueta-campo">Descripción (opcional)</label>
                          <input class="control-campo" type="text" name="micros_required_desc[x40]" placeholder="Qué se ve..." />
                        </div>
                      </div>

                      <!-- x100 -->
                      <div class="fila-imagen fila-obligatoria">
                        <div class="archivo-imagen">
                          <label class="etiqueta-campo">Imagen x100 <span class="obligatorio">*</span></label>
                          <input class="control-campo" type="file" name="micros_required_img[x100]" accept="image/*" required />
                        </div>
                        <div class="zoom-imagen">
                          <label class="etiqueta-campo">Zoom</label>
                          <input class="control-campo" type="text" value="x100" readonly />
                        </div>
                        <div class="descripcion-imagen">
                          <label class="etiqueta-campo">Descripción (opcional)</label>
                          <input class="control-campo" type="text" name="micros_required_desc[x100]" placeholder="Qué se ve..." />
                        </div>
                      </div>
                    </div>

                    <h3 class="subtarjeta-titulo" style="margin-top:16px;">Imágenes extra (opcional)</h3>

                    <div class="lista-imagenes" data-lista-imagenes="micro-extra">
                      <div class="fila-imagen">
                        <div class="archivo-imagen">
                          <label class="etiqueta-campo">Imagen</label>
                          <input class="control-campo" type="file" name="micros_extra_img[]" accept="image/*" />
                        </div>

                        <div class="zoom-imagen">
                          <label class="etiqueta-campo">Zoom</label>
                          <select class="control-campo" name="micros_extra_zoom[]">
                            <option value="">—</option>
                            <option value="x4">x4</option>
                            <option value="x10">x10</option>
                            <option value="x40">x40</option>
                            <option value="x100">x100</option>
                          </select>
                        </div>

                        <div class="descripcion-imagen">
                          <label class="etiqueta-campo">Descripción</label>
                          <input class="control-campo" type="text" name="micros_extra_desc[]" />
                        </div>

                        <div class="acciones-imagen">
                          <button class="boton boton-peligro" type="button" data-eliminar-fila>
                            Eliminar
                          </button>
                        </div>
                      </div>
                    </div>

                    <div class="acciones-imagenes">
                      <!-- ✅ CLAVE CORRECTA -->
                      <button class="boton boton-secundario" type="button" data-anadir-fila="micro-extra">
                        Añadir otra imagen
                      </button>
                    </div>

                    <template id="plantilla-micro-extra">
                      <div class="fila-imagen">
                        <div class="archivo-imagen">
                          <label class="etiqueta-campo">Imagen</label>
                          <input class="control-campo" type="file" name="micros_extra_img[]" accept="image/*" />
                        </div>

                        <div class="zoom-imagen">
                          <label class="etiqueta-campo">Zoom</label>
                          <select class="control-campo" name="micros_extra_zoom[]">
                            <option value="">—</option>
                            <option value="x4">x4</option>
                            <option value="x10">x10</option>
                            <option value="x40">x40</option>
                            <option value="x100">x100</option>
                          </select>
                        </div>

                        <div class="descripcion-imagen">
                          <label class="etiqueta-campo">Descripción</label>
                          <input class="control-campo" type="text" name="micros_extra_desc[]" />
                        </div>

                        <div class="acciones-imagen">
                          <button class="boton boton-peligro" type="button" data-eliminar-fila>
                            Eliminar
                          </button>
                        </div>
                      </div>
                    </template>
                  </div>
                </div>

                <div class="acciones">
                  <div class="acciones-izquierda">
                    <button class="boton boton-fantasma" type="button" id="botonVolverFase3">Volver</button>
                  </div>

                  <div class="acciones-derecha">
                    <button class="boton boton-secundario" type="button" id="botonGuardarFase4">
                      Guardar citodiagnóstico
                    </button>
                    <button class="boton boton-principal" type="submit" id="botonFinalizarEnviar">
                      Finalizar y enviar
                    </button>
                  </div>
                </div>
              </section>
            </form>
          </div>
        </article>
      </section>
    </main>

    <x-footer />
    <script src="{{ asset('js/formulario.js') }}"></script>
  </body>
</html>
