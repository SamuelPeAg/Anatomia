@props(['tiposMuestra'])

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

    <!-- IMÁGENES RECEPCIÓN -->
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
