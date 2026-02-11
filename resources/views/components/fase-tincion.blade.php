@props(['informe' => null])
<section class="fase" id="fase-3" data-fase="3">

  <form action="{{ $informe ? route('informes.update', $informe) : route('informes.sin-fase') }}" method="POST" enctype="multipart/form-data">
    @if(!$informe)
      <div class="alert alert-warning">
          Para guardar esta fase, primero debes completar y guardar la <strong>Fase 1 (Recepción)</strong>.
      </div>
    @endif
        @csrf
        @if($informe) @method('PUT') @endif
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
                value="{{ $informe->tincion_tipo ?? '' }}"
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
            >{{ $informe->tincion_observaciones ?? '' }}</textarea>
        </div>

        <!-- IMÁGENES TINCIÓN -->
        <x-upload-imagenes 
            :informe="$informe" 
            fase="tincion" 
            titulo="Imágenes de la tinción (opcional)"
        />

        <div class="acciones">
            <div class="acciones-izquierda">
                <button class="boton boton-fantasma" type="button" data-volver-paso="2">Volver</button>
            </div>

            <div class="acciones-derecha">
                <input type="hidden" name="fase_origen" value="3">
                <button class="boton boton-secundario" type="submit" name="stay" value="1">
                    Guardar tinción (incompleto)
                </button>
                <button class="boton boton-principal" type="submit" name="stay" value="0">
                    Siguiente fase
                </button>
            </div>
        </div>
    </form>
</section>
