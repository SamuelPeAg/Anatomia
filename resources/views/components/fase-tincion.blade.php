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
