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
