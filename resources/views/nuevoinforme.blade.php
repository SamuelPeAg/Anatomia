<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Nuevo informe — DAVANTE · MEDAC</title>

    <link rel="stylesheet" href="{{ asset('css/nuevoinforme.css') }}" />
  </head>

  <body>
    <x-header />

    <main class="page">
      <section class="container">
        <!-- CABECERA DE PÁGINA -->
        <header class="page-head">
          <div class="page-head__left">
            <h1 class="page-title">Nuevo informe</h1>
            <p class="page-subtitle">
              El informe se completa por fases:
              <strong>Recepción → Procesamiento → Tinción → Citodiagnóstico</strong>.
            </p>
          </div>

          <div class="page-head__right">
            <span class="badge badge--warning" id="estadoBadge">Incompleto</span>
          </div>
        </header>

        <!-- PASOS -->
        <nav class="steps" aria-label="Progreso del informe">
          <button class="step step--active" type="button" data-step="1">
            <span class="step__num">1</span>
            <span class="step__label">Recepción</span>
          </button>

          <button class="step" type="button" data-step="2">
            <span class="step__num">2</span>
            <span class="step__label">Procesamiento</span>
          </button>

          <button class="step" type="button" data-step="3">
            <span class="step__num">3</span>
            <span class="step__label">Tinción</span>
          </button>

          <button class="step" type="button" data-step="4">
            <span class="step__num">4</span>
            <span class="step__label">Citodiagnóstico</span>
          </button>
        </nav>

        <!-- TARJETA -->
        <article class="card">
          <div class="card__head">
            <h2 class="card__title" id="faseTitle">Fase 1 — Recepción</h2>
            <p class="card__hint">
              Campos con <span class="req">*</span> obligatorios.
            </p>
          </div>

          <div class="card__body">
            <form class="form" id="nuevoInformeForm" enctype="multipart/form-data">
              <!-- =======================================================
                   FASE 1 — RECEPCIÓN
              ======================================================== -->
              <section class="phase phase--active" id="phase-1" data-phase="1">
                <div class="grid">
                  <div class="field">
                    <label class="field__label" for="tipo_muestra">
                      Tipo de muestra <span class="req">*</span>
                    </label>
                    <select class="field__control" id="tipo_muestra" name="tipo_muestra" required>
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

                  <div class="field">
                    <label class="field__label" for="codigo_identificador">
                      Código identificador (auto)
                    </label>
                    <input
                      class="field__control"
                      type="text"
                      id="codigo_identificador"
                      name="codigo_identificador"
                      placeholder="Ej: B2530"
                      readonly
                    />
                    <small class="field__help" id="codigoHelp">
                      Se genera automáticamente al seleccionar el tipo.
                    </small>
                  </div>
                </div>

                <div class="field">
                  <label class="field__label" for="observaciones_llegada">
                    Observaciones de la llegada <span class="req">*</span>
                  </label>
                  <textarea
                    class="field__control"
                    id="observaciones_llegada"
                    name="observaciones_llegada"
                    rows="5"
                    placeholder="Estado del recipiente, incidencias, cantidad, etc."
                    required
                  ></textarea>
                </div>

                <div class="field field--hidden" id="organoField">
                  <label class="field__label" for="organo">
                    Órgano (solo biopsia) <span class="req">*</span>
                  </label>
                  <input class="field__control" type="text" id="organo" name="organo" />
                </div>

                <!-- IMÁGENES RECEPCIÓN (opcionales, añadir/quitar) -->
                <div class="subcard">
                  <div class="subcard__head">
                    <h3 class="subcard__title">Imágenes de recepción (opcional)</h3>
                    <p class="subcard__hint">Puedes adjuntar 0 o más imágenes.</p>
                  </div>
                  <div class="subcard__body">
                    <div class="img-list" data-img-list="recepcion">
                      <div class="img-row">
                        <div class="img-row__file">
                          <label class="field__label">Imagen</label>
                          <input class="field__control" type="file" name="recepcion_img[]" accept="image/*" />
                        </div>

                        <div class="img-row__desc">
                          <label class="field__label">Descripción (opcional)</label>
                          <input class="field__control" type="text" name="recepcion_desc[]" placeholder="Descripción..." />
                        </div>

                        <div class="img-row__actions">
                          <button class="btn btn--danger" type="button" data-remove-row>Eliminar</button>
                        </div>
                      </div>
                    </div>

                    <div class="img-actions">
                      <button class="btn btn--secondary" type="button" data-add-row="recepcion">
                        Añadir imagen
                      </button>
                    </div>

                    <template id="tpl-recepcion">
                      <div class="img-row">
                        <div class="img-row__file">
                          <label class="field__label">Imagen</label>
                          <input class="field__control" type="file" name="recepcion_img[]" accept="image/*" />
                        </div>

                        <div class="img-row__desc">
                          <label class="field__label">Descripción (opcional)</label>
                          <input class="field__control" type="text" name="recepcion_desc[]" placeholder="Descripción..." />
                        </div>

                        <div class="img-row__actions">
                          <button class="btn btn--danger" type="button" data-remove-row>Eliminar</button>
                        </div>
                      </div>
                    </template>
                  </div>
                </div>

                <div class="actions">
                  <div class="actions__left">
                    <a class="link" href="#">Volver</a>
                  </div>

                  <div class="actions__right">
                    <button class="btn btn--secondary" type="button" id="savePhase1">
                      Guardar recepción (incompleto)
                    </button>
                    <button class="btn btn--primary" type="button" id="toPhase2">
                      Siguiente fase
                    </button>
                  </div>
                </div>
              </section>

              <!-- =======================================================
                   FASE 2 — PROCESAMIENTO
              ======================================================== -->
              <section class="phase" id="phase-2" data-phase="2">
                <div class="field">
                  <label class="field__label" for="tipo_procesamiento">
                    Tipo de procesamiento <span class="req">*</span>
                  </label>
                  <select class="field__control" id="tipo_procesamiento" name="tipo_procesamiento" required>
                    <option value="">Selecciona…</option>
                    <option value="CITOCENTRIFUGADO">Citocentrifugado</option>
                    <option value="EXTENSION">Extensión / Frotis</option>
                    <option value="BLOQUE_CELULAR">Bloque celular</option>
                    <option value="FILTRADO">Filtrado</option>
                    <option value="OTRO">Otro</option>
                  </select>
                </div>

                <div class="field field--hidden" id="procesamientoOtroField">
                  <label class="field__label" for="procesamiento_otro">
                    Especifica el procesamiento <span class="req">*</span>
                  </label>
                  <input class="field__control" type="text" id="procesamiento_otro" name="procesamiento_otro" />
                </div>

                <div class="field">
                  <label class="field__label" for="observaciones_procesamiento">
                    Observaciones del procesamiento
                  </label>
                  <textarea
                    class="field__control"
                    id="observaciones_procesamiento"
                    name="observaciones_procesamiento"
                    rows="5"
                    placeholder="Tiempo, técnica, incidencias, calidad de la preparación..."
                  ></textarea>
                </div>

                <!-- IMÁGENES PROCESAMIENTO (opcionales, añadir/quitar) -->
                <div class="subcard">
                  <div class="subcard__head">
                    <h3 class="subcard__title">Imágenes del procesamiento (opcional)</h3>
                    <p class="subcard__hint">Puedes adjuntar 0 o más imágenes.</p>
                  </div>
                  <div class="subcard__body">
                    <div class="img-list" data-img-list="procesamiento">
                      <div class="img-row">
                        <div class="img-row__file">
                          <label class="field__label">Imagen</label>
                          <input class="field__control" type="file" name="procesamiento_img[]" accept="image/*" />
                        </div>

                        <div class="img-row__desc">
                          <label class="field__label">Descripción (opcional)</label>
                          <input class="field__control" type="text" name="procesamiento_desc[]" placeholder="Descripción..." />
                        </div>

                        <div class="img-row__actions">
                          <button class="btn btn--danger" type="button" data-remove-row>Eliminar</button>
                        </div>
                      </div>
                    </div>

                    <div class="img-actions">
                      <button class="btn btn--secondary" type="button" data-add-row="procesamiento">
                        Añadir imagen
                      </button>
                    </div>

                    <template id="tpl-procesamiento">
                      <div class="img-row">
                        <div class="img-row__file">
                          <label class="field__label">Imagen</label>
                          <input class="field__control" type="file" name="procesamiento_img[]" accept="image/*" />
                        </div>

                        <div class="img-row__desc">
                          <label class="field__label">Descripción (opcional)</label>
                          <input class="field__control" type="text" name="procesamiento_desc[]" placeholder="Descripción..." />
                        </div>

                        <div class="img-row__actions">
                          <button class="btn btn--danger" type="button" data-remove-row>Eliminar</button>
                        </div>
                      </div>
                    </template>
                  </div>
                </div>

                <div class="actions">
                  <div class="actions__left">
                    <button class="btn btn--ghost" type="button" id="backTo1">Volver</button>
                  </div>

                  <div class="actions__right">
                    <button class="btn btn--secondary" type="button" id="savePhase2">
                      Guardar procesamiento (incompleto)
                    </button>
                    <button class="btn btn--primary" type="button" id="toPhase3">
                      Siguiente fase
                    </button>
                  </div>
                </div>
              </section>

              <!-- =======================================================
                   FASE 3 — TINCIÓN
              ======================================================== -->
              <section class="phase" id="phase-3" data-phase="3">
                <div class="field">
                  <label class="field__label" for="tipo_tincion">
                    Tipo de tinción <span class="req">*</span>
                  </label>

                  <input
                    class="field__control"
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

                <div class="field">
                  <label class="field__label" for="observacion_tincion">
                    Observaciones de la tinción <span class="req">*</span>
                  </label>
                  <textarea
                    class="field__control"
                    id="observacion_tincion"
                    name="observacion_tincion"
                    rows="6"
                    placeholder="Qué se observa tras la tinción, calidad, hallazgos..."
                    required
                  ></textarea>
                </div>

                <!-- IMÁGENES TINCIÓN (opcionales, añadir/quitar) -->
                <div class="subcard">
                  <div class="subcard__head">
                    <h3 class="subcard__title">Imágenes de la tinción (opcional)</h3>
                    <p class="subcard__hint">Puedes adjuntar 0 o más imágenes.</p>
                  </div>
                  <div class="subcard__body">
                    <div class="img-list" data-img-list="tincion">
                      <div class="img-row">
                        <div class="img-row__file">
                          <label class="field__label">Imagen</label>
                          <input class="field__control" type="file" name="tincion_img[]" accept="image/*" />
                        </div>

                        <div class="img-row__desc">
                          <label class="field__label">Descripción (opcional)</label>
                          <input class="field__control" type="text" name="tincion_desc[]" placeholder="Descripción..." />
                        </div>

                        <div class="img-row__actions">
                          <button class="btn btn--danger" type="button" data-remove-row>Eliminar</button>
                        </div>
                      </div>
                    </div>

                    <div class="img-actions">
                      <button class="btn btn--secondary" type="button" data-add-row="tincion">
                        Añadir imagen
                      </button>
                    </div>

                    <template id="tpl-tincion">
                      <div class="img-row">
                        <div class="img-row__file">
                          <label class="field__label">Imagen</label>
                          <input class="field__control" type="file" name="tincion_img[]" accept="image/*" />
                        </div>

                        <div class="img-row__desc">
                          <label class="field__label">Descripción (opcional)</label>
                          <input class="field__control" type="text" name="tincion_desc[]" placeholder="Descripción..." />
                        </div>

                        <div class="img-row__actions">
                          <button class="btn btn--danger" type="button" data-remove-row>Eliminar</button>
                        </div>
                      </div>
                    </template>
                  </div>
                </div>

                <div class="actions">
                  <div class="actions__left">
                    <button class="btn btn--ghost" type="button" id="backTo2">Volver</button>
                  </div>

                  <div class="actions__right">
                    <button class="btn btn--secondary" type="button" id="savePhase3">
                      Guardar tinción (incompleto)
                    </button>
                    <button class="btn btn--primary" type="button" id="toPhase4">
                      Siguiente fase
                    </button>
                  </div>
                </div>
              </section>

              <!-- =======================================================
                   FASE 4 — CITODIAGNÓSTICO
              ======================================================== -->
              <section class="phase" id="phase-4" data-phase="4">
                <div class="field">
                  <label class="field__label" for="citodiagnostico">
                    Citodiagnóstico <span class="req">*</span>
                  </label>
                  <textarea
                    class="field__control"
                    id="citodiagnostico"
                    name="citodiagnostico"
                    rows="6"
                    placeholder="Descripción diagnóstica, hallazgos citológicos / tisulares..."
                    required
                  ></textarea>
                </div>

                <div class="subcard">
                  <div class="subcard__head">
                    <h3 class="subcard__title">Imágenes microscópicas obligatorias</h3>
                    <p class="subcard__hint">Debes adjuntar 4 imágenes: x4, x10, x40 y x100.</p>
                  </div>

                  <div class="subcard__body">
                    <div class="img-list" id="requiredImgList">
                      <!-- x4 -->
                      <div class="img-row img-row--required">
                        <div class="img-row__file">
                          <label class="field__label">Imagen x4 <span class="req">*</span></label>
                          <input class="field__control" type="file" name="micros_required_img[x4]" accept="image/*" required />
                        </div>
                        <div class="img-row__zoom">
                          <label class="field__label">Zoom</label>
                          <input class="field__control" type="text" value="x4" readonly />
                        </div>
                        <div class="img-row__desc">
                          <label class="field__label">Descripción (opcional)</label>
                          <input class="field__control" type="text" name="micros_required_desc[x4]" placeholder="Qué se ve..." />
                        </div>
                      </div>

                      <!-- x10 -->
                      <div class="img-row img-row--required">
                        <div class="img-row__file">
                          <label class="field__label">Imagen x10 <span class="req">*</span></label>
                          <input class="field__control" type="file" name="micros_required_img[x10]" accept="image/*" required />
                        </div>
                        <div class="img-row__zoom">
                          <label class="field__label">Zoom</label>
                          <input class="field__control" type="text" value="x10" readonly />
                        </div>
                        <div class="img-row__desc">
                          <label class="field__label">Descripción (opcional)</label>
                          <input class="field__control" type="text" name="micros_required_desc[x10]" placeholder="Qué se ve..." />
                        </div>
                      </div>

                      <!-- x40 -->
                      <div class="img-row img-row--required">
                        <div class="img-row__file">
                          <label class="field__label">Imagen x40 <span class="req">*</span></label>
                          <input class="field__control" type="file" name="micros_required_img[x40]" accept="image/*" required />
                        </div>
                        <div class="img-row__zoom">
                          <label class="field__label">Zoom</label>
                          <input class="field__control" type="text" value="x40" readonly />
                        </div>
                        <div class="img-row__desc">
                          <label class="field__label">Descripción (opcional)</label>
                          <input class="field__control" type="text" name="micros_required_desc[x40]" placeholder="Qué se ve..." />
                        </div>
                      </div>

                      <!-- x100 -->
                      <div class="img-row img-row--required">
                        <div class="img-row__file">
                          <label class="field__label">Imagen x100 <span class="req">*</span></label>
                          <input class="field__control" type="file" name="micros_required_img[x100]" accept="image/*" required />
                        </div>
                        <div class="img-row__zoom">
                          <label class="field__label">Zoom</label>
                          <input class="field__control" type="text" value="x100" readonly />
                        </div>
                        <div class="img-row__desc">
                          <label class="field__label">Descripción (opcional)</label>
                          <input class="field__control" type="text" name="micros_required_desc[x100]" placeholder="Qué se ve..." />
                        </div>
                      </div>
                    </div>

                    <h3 class="subcard__title" style="margin-top:16px;">Imágenes extra (opcional)</h3>

                    <div class="img-list" data-img-list="micro-extra">
                      <div class="img-row">
                        <div class="img-row__file">
                          <label class="field__label">Imagen</label>
                          <input class="field__control" type="file" name="micros_extra_img[]" accept="image/*" />
                        </div>

                        <div class="img-row__zoom">
                          <label class="field__label">Zoom</label>
                          <select class="field__control" name="micros_extra_zoom[]">
                            <option value="">—</option>
                            <option value="x4">x4</option>
                            <option value="x10">x10</option>
                            <option value="x40">x40</option>
                            <option value="x100">x100</option>
                          </select>
                        </div>

                        <div class="img-row__desc">
                          <label class="field__label">Descripción</label>
                          <input class="field__control" type="text" name="micros_extra_desc[]" />
                        </div>

                        <div class="img-row__actions">
                          <button class="btn btn--danger" type="button" data-remove-row>Eliminar</button>
                        </div>
                      </div>
                    </div>

                    <div class="img-actions">
                      <button class="btn btn--secondary" type="button" data-add-row="micro-extra">
                        Añadir otra imagen
                      </button>
                    </div>

                    <template id="tpl-micro-extra">
                      <div class="img-row">
                        <div class="img-row__file">
                          <label class="field__label">Imagen</label>
                          <input class="field__control" type="file" name="micros_extra_img[]" accept="image/*" />
                        </div>

                        <div class="img-row__zoom">
                          <label class="field__label">Zoom</label>
                          <select class="field__control" name="micros_extra_zoom[]">
                            <option value="">—</option>
                            <option value="x4">x4</option>
                            <option value="x10">x10</option>
                            <option value="x40">x40</option>
                            <option value="x100">x100</option>
                          </select>
                        </div>

                        <div class="img-row__desc">
                          <label class="field__label">Descripción</label>
                          <input class="field__control" type="text" name="micros_extra_desc[]" />
                        </div>

                        <div class="img-row__actions">
                          <button class="btn btn--danger" type="button" data-remove-row>Eliminar</button>
                        </div>
                      </div>
                    </template>
                  </div>
                </div>

                <div class="actions">
                  <div class="actions__left">
                    <button class="btn btn--ghost" type="button" id="backTo3">Volver</button>
                  </div>

                  <div class="actions__right">
                    <button class="btn btn--secondary" type="button" id="savePhase4">
                      Guardar citodiagnóstico
                    </button>
                    <button class="btn btn--primary" type="submit" id="submitFinal">
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
