<?php

namespace App\Http\Controllers;

use App\Models\Expediente;
use App\Models\Imagen;
use App\Models\Informe;
use App\Models\Tipomuestra;
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
            $search = trim($request->search);
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

        $informes = $query->paginate(10)->onEachSide(2)->withQueryString();

        $informes->getCollection()->transform(function ($informe) {
            $numIncompleta = $this->getPrimeraFaseIncompleta($informe);
            
            if ($numIncompleta === null) {
                $informe->siguiente_fase = 'Finalizado'; 
                $informe->fase_n = 4;
            } else {
                $nombres = [1 => 'Recepción', 2 => 'Procesamiento', 3 => 'Tinción', 4 => 'Citodiagnóstico'];
                $informe->siguiente_fase = $nombres[$numIncompleta];
                $informe->fase_n = $numIncompleta;
            }
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
            'tiposMuestra' => Tipomuestra::all()
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

        $tipo = Tipomuestra::where('prefijo', $request->tipo_muestra)->firstOrFail();
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

        return $this->redirectAfterSave($request, $informe, 'Informe creado.');
    }

    public function edit(Informe $informe)
    {
        $fase = request('fase') ?: ($this->getPrimeraFaseIncompleta($informe) ?: 4);
        
        return view('nuevoinforme', [
            'informe' => $informe,
            'numeroFase' => $fase,
            'faseActual' => $fase,
            'imagenesMicroExtras' => $informe->imagenes->where('fase', 'microscopio')->where('obligatoria', 0),
            'esEdicion' => true,
            'fasesCompletas' => [
                1 => !empty($informe->recepcion_observaciones),
                2 => !empty($informe->procesamiento_tipo),
                3 => !empty($informe->tincion_tipo),
                4 => !empty($informe->citodiagnostico) && 
                     $informe->imagenes()->where('fase', 'microscopio')->where('obligatoria', 1)->count() >= 4
            ],
            'tiposMuestra' => Tipomuestra::all()
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
        }

        $informe->update($data);
        $this->procesarImagenes($request, $informe);

        // Refrescar para asegurar que las imágenes recién guardadas se cuenten en el check de estado
        $informe->refresh();

        // El estado solo pasa a 'completo' si getPrimeraFaseIncompleta devuelve null (todo lleno)
        $primeraPendiente = $this->getPrimeraFaseIncompleta($informe);
        $informe->update(['estado' => ($primeraPendiente === null) ? 'completo' : 'incompleto']);

        return $this->redirectAfterSave($request, $informe, 'Progreso guardado.');
    }

    private function redirectAfterSave(Request $request, Informe $informe, string $baseMsg): RedirectResponse
    {
        $stay = $request->input('stay');

        if ($stay == '2') return redirect()->route('revision')->with('success', $baseMsg);
        if ($stay == '1') return back()->with('success', $baseMsg);

        $next = $this->getPrimeraFaseIncompleta($informe);
        if (!$next) return redirect()->route('revision')->with('success', 'Informe finalizado correctamente.');
        
        return redirect()->route('informes.edit', ['informe' => $informe, 'fase' => $next])
            ->with('success', $baseMsg . ' Redirigiendo a la fase pendiente.');
    }

    /**
     * Devuelve el número (1-4) de la primera fase que le falta información obligatoria.
     * Devuelve null si todas (1, 2, 3 y 4) están realmente completas.
     */
    private function getPrimeraFaseIncompleta(Informe $informe)
    {
        if (empty($informe->recepcion_observaciones)) return 1;
        if (empty($informe->procesamiento_tipo)) return 2;
        if (empty($informe->tincion_tipo)) return 3;
        if (empty($informe->citodiagnostico)) return 4;
        
        // Verificación de los 4 aumentos obligatorios en fase 4
        $imgsFase4 = $informe->imagenes()->where('fase', 'microscopio')->where('obligatoria', 1)->pluck('zoom')->toArray();
        $requeridos = ['x4', 'x10', 'x40', 'x100'];
        foreach ($requeridos as $z) {
            if (!in_array($z, $imgsFase4)) return 4;
        }

        return null; // Absolutamente todo completo
    }

    // --- Métodos Privados y Auxiliares ---

    private function obtenerExpedienteId(Request $request)
    {
        return $request->filled('paciente_correo') 
            ? Expediente::firstOrCreate(
                ['correo' => $request->paciente_correo],
                ['nombre' => $request->paciente_nombre ?? 'Paciente sin nombre']
              )->id 
            : null;
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
        $configs = [
            'recepcion'     => ['img' => 'recepcion_img', 'desc' => 'recepcion_desc'],
            'procesamiento' => ['img' => 'procesamiento_img', 'desc' => 'procesamiento_desc'],
            'tincion'       => ['img' => 'tincion_img', 'desc' => 'tincion_desc'],
            'microscopio'   => ['img' => 'micros_extra_img', 'desc' => 'micros_extra_desc', 'zoom' => 'micros_extra_zoom']
        ];

        foreach ($configs as $fase => $c) {
            if (!$request->hasFile($c['img'])) continue;
            
            $files = collect($request->file($c['img']))->flatten();
            $descs = $request->input($c['desc'], []);
            $zooms = isset($c['zoom']) ? $request->input($c['zoom'], []) : [];
            $count = $informe->imagenes()->where('fase', $fase)->count();

            foreach ($files as $i => $file) {
                if (($count + $i) < 6) {
                    $this->guardarImagen($file, $informe, $fase, $descs[$i] ?? null, $zooms[$i] ?? null, false);
                }
            }
        }

        // Obligatorias Microscopio
        foreach (['x4', 'x10', 'x40', 'x100'] as $z) {
            if (!$request->hasFile("micro_{$z}_img")) continue;
            
            $file = collect($request->file("micro_{$z}_img"))->flatten()->first();
            $desc = $request->input("micro_{$z}_desc.0");
            
            if ($informe->imagenes()->where(['fase' => 'microscopio', 'zoom' => $z])->count() < 6) {
                $this->guardarImagen($file, $informe, 'microscopio', $desc, $z, true);
            }
        }
    }

    /**
     * Método unificado para guardar una sola imagen.
     * Maneja subida de archivo, creación en BD y errores.
     */
    private function guardarImagen($file, Informe $informe, string $fase, ?string $descripcion, ?string $zoom, bool $obligatoria)
    {
        if (!$file || !$file->isValid()) {
            \Log::warning("Archivo de imagen no válido para informe {$informe->id} en fase $fase");
            return;
        }

        try {
            $path = $file->store("informes/{$informe->id}/{$fase}", 'public');

            if (!$path) {
                throw new \Exception("El almacenamiento falló y no devolvió una ruta.");
            }

            Imagen::create([
                'informe_id'  => $informe->id,
                'fase'        => $fase,
                'ruta'        => $path,
                'descripcion' => $descripcion,
                'zoom'        => $zoom,
                'obligatoria' => $obligatoria
            ]);
            
            \Log::info("Imagen guardada correctamente: $path");
            
        } catch (\Exception $e) {
            \Log::error("ERROR CRÍTICO EN PRODUCCIÓN AL GUARDAR IMAGEN: " . $e->getMessage());
        }
    }
}
