@props(['informe' => null, 'imagenesExtras' => collect([])])
<section class="fase" id="fase-4" data-fase="4">
  <form action="{{ $informe ? route('informes.update', $informe) : route('informes.sin-fase') }}" method="POST" enctype="multipart/form-data">
    @if(!$informe)
      <div class="alert alert-warning">
          Para guardar esta fase, primero debes completar y guardar la <strong>Fase 1 (Recepción)</strong>.
      </div>
    @endif
        @csrf
        @if($informe) @method('PUT') @endif
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
            >{{ $informe->citodiagnostico ?? '' }}</textarea>
        </div>

        <div class="subtarjeta">
            <div class="subtarjeta-cabecera">
                <h3 class="subtarjeta-titulo">Imágenes microscópicas obligatorias</h3>
                <p class="subtarjeta-ayuda">Debes adjuntar 4 imágenes: x4, x10, x40 y x100.</p>
            </div>

            <div class="subtarjeta-cuerpo">
                <x-upload-imagenes :informe="$informe" fase="microscopio" zoom="x4" titulo="Aumento 4x (Obligatorio)" :required="true" input-name="micro_x4_img[]" input-name-desc="micro_x4_desc[]" />
                
                <x-upload-imagenes :informe="$informe" fase="microscopio" zoom="x10" titulo="Aumento 10x (Obligatorio)" :required="true" input-name="micro_x10_img[]" input-name-desc="micro_x10_desc[]" />
                
                <x-upload-imagenes :informe="$informe" fase="microscopio" zoom="x40" titulo="Aumento 40x (Obligatorio)" :required="true" input-name="micro_x40_img[]" input-name-desc="micro_x40_desc[]" />
                
                <x-upload-imagenes :informe="$informe" fase="microscopio" zoom="x100" titulo="Aumento 100x (Obligatorio)" :required="true" input-name="micro_x100_img[]" input-name-desc="micro_x100_desc[]" />

                <x-upload-imagenes :informe="$informe" fase="microscopio" zoom="extra" titulo="Otras imágenes (Opcional)" :required="false" input-name="micros_extra_img[]" input-name-desc="micros_extra_desc[]" />
            </div>
        </div>

        <div class="acciones">
            <div class="acciones-izquierda">
                <button class="boton boton-fantasma" type="button" data-volver-paso="3">Volver</button>
            </div>

            <div class="acciones-derecha">
                <input type="hidden" name="fase_origen" value="4">
                <button class="boton boton-secundario" type="submit" name="stay" value="2">
                    Revisión
                </button>
                <button class="boton boton-principal btn-finalizar-informe" type="submit" name="stay" value="0">
                    Finalizar y enviar
                </button>
            </div>
        </div>
    </form>
</section>
