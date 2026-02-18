<?php

namespace App\Http\Controllers;

use App\Models\Expediente;
use App\Models\Imagen;
use App\Models\Informe;
use App\Models\TipoMuestra;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class InformeController extends Controller
{
    /**
     * Maneja intentos de guardar fases posteriores sin haber guardado la Fase 1.
     */
    public function errorSinFase()
    {
        return redirect()->back()
            ->with('error', 'No se puede guardar esta fase porque el informe aún no ha sido creado. Por favor, ve a la "Fase 1 (Recepción)", completa los datos obligatorios y pulsa en "Guardar y Continuar".');
    }

    /**
     * Elimina una imagen del sistema y del almacenamiento.
     */
    public function destroyImagen(Imagen $imagen)
    {
        Log::info("Solicitud de borrado para imagen ID: " . $imagen->id);

        try {
            // Borrar archivo físico si existe
            if ($imagen->ruta && Storage::disk('public')->exists($imagen->ruta)) {
                Storage::disk('public')->delete($imagen->ruta);
            }

            // Borrar registro DB
            $imagen->delete();

            // Si la petición viene por AJAX (JS), devolver JSON
            if (request()->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Imagen eliminada']);
            }

            return redirect()->back()->with('success', 'Imagen eliminada correctamente.');

        } catch (\Exception $e) {
            Log::error("Error al borrar imagen ID {$imagen->id}: " . $e->getMessage());

            if (request()->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Error del servidor'], 500);
            }

            return redirect()->back()->with('error', 'Error al eliminar la imagen.');
        }
    }

    /**
     * Muestra el listado de informes.
     */
    public function index(Request $request)
    {
        $query = Informe::with(['tipo', 'expediente'])->orderBy('created_at', 'desc');

        // Lógica de filtrado:
        // 1. Prioridad: Si hay búsqueda (search), mostramos todos los resultados que coincidan
        // independientemente de la fecha.
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('codigo_identificador', 'like', "%$search%")
                  ->orWhereHas('expediente', function($qe) use ($search) {
                      $qe->where('nombre', 'like', "%$search%")
                         ->orWhere('id', 'like', "%$search%");
                  });
            });
        } 
        // 2. Si NO hay búsqueda, aplicamos filtros de fecha (a menos que se pida "mostrar_todos")
        elseif (!$request->has('mostrar_todos')) {
            if ($request->filled('fecha')) {
                $query->whereDate('created_at', $request->fecha);
            } else {
                $query->whereDate('created_at', now());
            }
        }

        $informes = $query->paginate(10)->withQueryString();

        $informes->getCollection()->transform(function ($informe) {
            $faseInfo = $this->getFaseInfo($informe);
            $informe->siguiente_fase = $faseInfo['nombre'];
            $informe->fase_n = $faseInfo['numero'];
            return $informe;
        });

        return view('revision', compact('informes'));
    }

    /**
     * Marca un informe como revisado (solo Admin).
     */
    public function revisar(Informe $informe)
    {
        if (!auth()->user()->isAdmin()) {
            return back()->with('error', 'No tienes permisos para realizar esta acción.');
        }

        if ($informe->estado !== 'completo') {
            return back()->with('error', 'No se puede revisar un informe que no está marcado como COMPLETO (debe finalizarse la Fase 4).');
        }

        $informe->update(['estado' => 'revisado']);

        return back()->with('success', 'El informe ha sido marcado como REVISADO y bloqueado.');
    }

    /**
     * Elimina el informe y sus imágenes (solo Admin).
     */
    public function destroy(Informe $informe)
    {
        if (!auth()->user()->isAdmin()) {
            return back()->with('error', 'No tienes permisos para borrar informes.');
        }

        // Primero borrar imágenes del disco
        foreach ($informe->imagenes as $img) {
            if ($img->ruta && Storage::disk('public')->exists($img->ruta)) {
                Storage::disk('public')->delete($img->ruta);
            }
        }

        $informe->delete();

        return redirect()->route('revision')->with('success', 'Informe eliminado permanentemente.');
    }

    /**
     * Muestra el formulario para crear un nuevo informe.
     */
    public function create()
    {
        return view('nuevoinforme', [
            'informe' => null,
            'esEdicion' => false,
            'faseActual' => 1,
            'numeroFase' => 1,
            'fasesCompletas' => [1 => false, 2 => false, 3 => false, 4 => false],
            'imagenesMicroExtras' => collect([]),
            'tiposMuestra' => TipoMuestra::all()
        ]);
    }

    /**
     * Almacena un nuevo informe en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tipo_muestra' => 'required|string',
            'codigo_identificador' => 'required|string|unique:informes,codigo_identificador',
            'observaciones_llegada' => 'nullable|string',
            'paciente_nombre' => 'nullable|string|max:255',
            'paciente_correo' => 'nullable|email|max:255',
        ]);

        $tipo = TipoMuestra::where('prefijo', $request->tipo_muestra)->firstOrFail();
        $expedienteId = $this->obtenerExpedienteId($request);

        $informe = Informe::create([
            'expediente_id' => $expedienteId,
            'tipo_id' => $tipo->id,
            'anio' => now()->year,
            'correlativo' => (int) substr($request->codigo_identificador, strlen($tipo->prefijo) + 2),
            'codigo_identificador' => $request->codigo_identificador,
            'estado' => 'incompleto',
            'recepcion_observaciones' => $request->observaciones_llegada,
            'recepcion_organo' => $request->organo,
        ]);

        $this->procesarImagenes($request, $informe);

        if ($request->input('stay') == '2') {
            return redirect()->route('revision')->with('success', 'Informe creado y guardado.');
        }

        if ($request->input('stay') == '1') {
            return redirect()->route('informes.edit', ['informe' => $informe, 'fase' => 1])
                ->with('success', 'Recepción guardada.');
        }

        return redirect()->route('informes.edit', ['informe' => $informe, 'fase' => 2])
            ->with('success', 'Recepción completada. Continuando a Procesamiento.');
    }

    /**
     * Muestra el formulario de edición de un informe.
     */
    public function edit(Informe $informe)
    {
        $numeroFase = request('fase') ?: $this->getFaseInfo($informe)['numero'];
        
        return view('nuevoinforme', [
            'informe' => $informe,
            'numeroFase' => $numeroFase,
            'faseActual' => $numeroFase,
            'imagenesMicroExtras' => $informe->imagenes->where('fase', 'microscopio')->where('obligatoria', 0),
            'esEdicion' => true,
            'fasesCompletas' => [
                1 => !empty($informe->recepcion_observaciones),
                2 => !empty($informe->procesamiento_tipo),
                3 => !empty($informe->tincion_tipo),
                4 => !empty($informe->citodiagnostico)
            ],
            'tiposMuestra' => TipoMuestra::all()
        ]);
    }

    /**
     * Actualiza un informe existente.
     */
    public function update(Request $request, Informe $informe)
    {
        if ($informe->estado === 'revisado') {
            return back()->with('error', 'Este informe ya ha sido revisado y no puede ser modificado.');
        }

        // Validación estricta para Fase 4 (Microscopio)
        if ($errores = $this->validarRequisitosMicroscopio($request, $informe)) {
            return back()->withErrors($errores)->withInput()->with('error', 'Faltan imágenes obligatorias.');
        }

        $data = [];

        // Mapeo dinámico de campos según fase
        if ($request->has('observaciones_llegada')) {
            $data['recepcion_observaciones'] = $request->observaciones_llegada;
            $data['recepcion_organo'] = $request->organo;
            
            if ($id = $this->obtenerExpedienteId($request)) {
                $data['expediente_id'] = $id;
            }
        }

        if ($request->has('tipo_procesamiento')) {
            $data['procesamiento_tipo'] = $request->tipo_procesamiento;
            $data['procesamiento_otro'] = $request->procesamiento_otro;
            $data['procesamiento_observaciones'] = $request->observaciones_procesamiento;
        }

        if ($request->has('tipo_tincion')) {
            $data['tincion_tipo'] = $request->tipo_tincion;
            $data['tincion_observaciones'] = $request->observacion_tincion;
        }

        if ($request->has('citodiagnostico')) {
            $data['citodiagnostico'] = $request->citodiagnostico;
            $data['estado'] = 'completo';
        }

        $informe->update($data);
        $this->procesarImagenes($request, $informe);

        if ($request->input('stay') == '2') {
            return redirect()->route('revision')->with('success', 'Progreso guardado correctamente.');
        }

        if ($request->input('stay') == '1') {
            return back()->with('success', 'Progreso guardado.');
        }

        $faseActual = (int) $request->input('fase_origen', 1);

        if ($faseActual == 4) {
            return redirect()->route('revision')->with('success', 'Informe finalizado correctamente.');
        }

        $siguienteFase = ($faseActual < 4) ? $faseActual + 1 : 4;

        return redirect()->route('informes.edit', ['informe' => $informe, 'fase' => $siguienteFase])
            ->with('success', 'Fase completada. Continuando...');
    }

    // --- Métodos Privados y Auxiliares ---

    private function getFaseInfo($informe)
    {
        if (empty($informe->recepcion_observaciones)) return ['nombre' => 'Recepción', 'numero' => 1];
        if (empty($informe->procesamiento_tipo)) return ['nombre' => 'Procesamiento', 'numero' => 2];
        if (empty($informe->tincion_tipo)) return ['nombre' => 'Tinción', 'numero' => 3];
        if (empty($informe->citodiagnostico)) return ['nombre' => 'Citodiagnóstico', 'numero' => 4];
        
        return ['nombre' => 'Finalizado', 'numero' => 4];
    }

    private function obtenerExpedienteId(Request $request)
    {
        if ($request->filled('paciente_correo')) {
            $expediente = Expediente::firstOrCreate(
                ['correo' => $request->paciente_correo],
                ['nombre' => $request->paciente_nombre ?? 'Paciente sin nombre']
            );
            return $expediente->id;
        }
        return null;
    }

    private function validarRequisitosMicroscopio(Request $request, Informe $informe)
    {
        if (!$request->has('citodiagnostico')) {
            return null;
        }

        $zooms = ['x4', 'x10', 'x40', 'x100'];
        $faltantes = [];
        
        $imagenesExistentes = $informe->imagenes()
            ->where('fase', 'microscopio')
            ->whereIn('zoom', $zooms)
            ->pluck('zoom')
            ->toArray();

        foreach ($zooms as $zoom) {
            $existe = in_array($zoom, $imagenesExistentes);
            $viene = $request->hasFile("micro_{$zoom}_img");
            
            if (!$existe && !$viene) {
                $faltantes[] = $zoom;
            }
        }

        if (!empty($faltantes)) {
            return ['imagenes' => 'Es obligatorio adjuntar imágenes para los aumentos: ' . implode(', ', $faltantes)];
        }

        return null;
    }

    private function procesarImagenes(Request $request, Informe $informe)
    {
        // 1. Procesar Fases Estándar y Extras (Arrays de archivos)
        $configuraciones = [
            'recepcion'     => ['img' => 'recepcion_img', 'desc' => 'recepcion_desc'],
            'procesamiento' => ['img' => 'procesamiento_img', 'desc' => 'procesamiento_desc'],
            'tincion'       => ['img' => 'tincion_img', 'desc' => 'tincion_desc'],
            'microscopio'   => ['img' => 'micros_extra_img', 'desc' => 'micros_extra_desc', 'zoom' => 'micros_extra_zoom'] // Extras
        ];

        foreach ($configuraciones as $faseKey => $conf) {
            $faseBD = ($faseKey === 'microscopio') ? 'microscopio' : $faseKey;

            if ($request->hasFile($conf['img'])) {
                $rawFiles = $request->file($conf['img']);
                
                // Aplanamos el array de archivos (por si vienen de varios inputs file[])
                $files = [];
                if (is_array($rawFiles)) {
                    foreach ($rawFiles as $item) {
                        if (is_array($item)) {
                            $files = array_merge($files, $item);
                        } else {
                            $files[] = $item;
                        }
                    }
                } else {
                    $files = [$rawFiles];
                }

                $descs = $request->input($conf['desc'], []);
                $zoomsInput = isset($conf['zoom']) ? $request->input($conf['zoom'], []) : [];

                foreach ($files as $i => $file) {
                    $this->guardarImagen(
                        $file, 
                        $informe, 
                        $faseBD, 
                        $descs[$i] ?? null, 
                        $zoomsInput[$i] ?? null, 
                        false 
                    );
                }
            }
        }

        // 2. Procesar Microscopio OBLIGATORIAS (Inputs planos por Zoom)
        foreach (['x4', 'x10', 'x40', 'x100'] as $zoom) {
            $inputImg = "micro_{$zoom}_img";
            
            if ($request->hasFile($inputImg)) {
                $rawFiles = $request->file($inputImg);
                
                // Aplanamos también aquí por si acaso
                $files = [];
                if (is_array($rawFiles)) {
                    foreach ($rawFiles as $item) {
                        if (is_array($item)) {
                            $files = array_merge($files, $item);
                        } else {
                            $files[] = $item;
                        }
                    }
                } else {
                    $files = [$rawFiles];
                }

                $descs = $request->input("micro_{$zoom}_desc", []);

                foreach ($files as $i => $file) {
                    $this->guardarImagen(
                        $file, 
                        $informe, 
                        'microscopio', 
                        $descs[$i] ?? null, 
                        $zoom, 
                        true 
                    );
                }
            }
        }
    }

    /**
     * Método unificado para guardar una sola imagen.
     * Maneja subida de archivo, creación en BD y errores.
     */
    private function guardarImagen($file, Informe $informe, string $fase, ?string $descripcion, ?string $zoom, bool $obligatoria)
    {
        if (!$file || !$file->isValid()) return;

        try {
            $path = $file->store("informes/{$informe->id}/{$fase}", 'public');

            Imagen::create([
                'informe_id'  => $informe->id,
                'fase'        => $fase,
                'ruta'        => $path,
                'descripcion' => $descripcion,
                'zoom'        => $zoom,
                'obligatoria' => $obligatoria
            ]);
            
        } catch (\Exception $e) {
            Log::error("Error al guardar imagen de fase $fase: " . $e->getMessage());
        }
    }
}
