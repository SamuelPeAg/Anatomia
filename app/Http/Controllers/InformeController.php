<?php

namespace App\Http\Controllers;

use App\Models\Informe;
use App\Models\TipoMuestra;
use App\Models\Imagen;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class InformeController extends Controller
{
    public function destroyImagen(Imagen $imagen)
    {
        // Borrar archivo físico
        if (Storage::disk('public')->exists($imagen->ruta)) {
            Storage::disk('public')->delete($imagen->ruta);
        }

        // Borrar registro DB
        $imagen->delete();

        return redirect()->back()->with('success', 'Imagen eliminada correctamente.');
    }

    public function index()
    {
        $informes = Informe::with('tipo')->orderBy('created_at', 'desc')->get();

        foreach ($informes as $informe) {
            $faseInfo = $this->getSiguienteFaseInfo($informe);
            $informe->siguiente_fase = $faseInfo['nombre'];
            $informe->fase_n = $faseInfo['numero'];
        }

        return view('revision', compact('informes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipo_muestra' => 'required|string',
            'codigo_identificador' => 'required|string|unique:informes,codigo_identificador',
            'observaciones_llegada' => 'required|string',
            'paciente_nombre' => 'nullable|string|max:255',
            'paciente_correo' => 'nullable|email|max:255',
        ]);

        $tipo = TipoMuestra::where('prefijo', $request->tipo_muestra)->firstOrFail();
        
        $expedienteId = null;
        if ($request->filled('paciente_correo')) {
            $expediente = \App\Models\Expediente::firstOrCreate(
                ['correo' => $request->paciente_correo],
                ['nombre' => $request->paciente_nombre ?? 'Paciente sin nombre']
            );
            $expedienteId = $expediente->id;
        }

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

        return redirect()->route('informes.edit', $informe)
            ->with('success', 'Recepción guardada correctamente.');
    }

    public function edit(Informe $informe)
    {
        $numeroFase = request('fase') ?: $this->getSiguienteFaseInfo($informe)['numero'];
        
        return view('nuevoinforme', compact('informe', 'numeroFase'));
    }

    public function update(Request $request, Informe $informe)
    {
        $data = [];

        // Mapeo de campos por fase detectada en el request
        if ($request->has('observaciones_llegada')) {
            $data['recepcion_observaciones'] = $request->observaciones_llegada;
            $data['recepcion_organo'] = $request->organo;

            // Vincular/Actualizar expediente si se envían datos
            if ($request->filled('paciente_correo')) {
                $expediente = \App\Models\Expediente::firstOrCreate(
                    ['correo' => $request->paciente_correo],
                    ['nombre' => $request->paciente_nombre ?? 'Paciente sin nombre']
                );
                $data['expediente_id'] = $expediente->id;
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

        if ($request->input('stay') == '1') {
            return back()->with('success', 'Progreso guardado.');
        }

        $faseActual = (int) $request->input('fase_origen', 1);
        $siguienteFase = ($faseActual < 4) ? $faseActual + 1 : 4;

        return redirect()->route('informes.edit', ['informe' => $informe, 'fase' => $siguienteFase])
            ->with('success', 'Información actualizada.');
    }

    private function getSiguienteFaseInfo($informe): array
    {
        if (empty($informe->recepcion_observaciones)) return ['nombre' => 'Recepción', 'numero' => 1];
        if (empty($informe->procesamiento_tipo)) return ['nombre' => 'Procesamiento', 'numero' => 2];
        if (empty($informe->tincion_tipo)) return ['nombre' => 'Tinción', 'numero' => 3];
        if (empty($informe->citodiagnostico)) return ['nombre' => 'Citodiagnóstico', 'numero' => 4];
        
        return ['nombre' => 'Finalizado', 'numero' => 4];
    }

    private function procesarImagenes(Request $request, Informe $informe)
    {
        Log::info('Inicio procesarImagenes Informe ID: ' . $informe->id);
        
        // 1. Recepción
        if ($request->hasFile('recepcion_img')) {
            $archivos = $request->file('recepcion_img');
            $descripciones = $request->input('recepcion_desc', []);
            
            Log::info('Imágenes recepción detectadas: ' . count($archivos));

            foreach ($archivos as $index => $file) {
                if ($file && $file->isValid()) {
                    $path = $file->store("informes/{$informe->id}/recepcion", 'public');
                    
                    Imagen::create([
                        'informe_id' => $informe->id,
                        'fase' => 'recepcion',
                        'ruta' => $path,
                        'descripcion' => $descripciones[$index] ?? null,
                    ]);
                    Log::info("Imagen guardada en: $path");
                } else {
                    Log::warning("Archivo inválido en índice $index: " . ($file ? $file->getErrorMessage() : 'NULL'));
                }
            }
        }

        // 2. Procesamiento
        if ($request->hasFile('procesamiento_img')) {
            $archivos = $request->file('procesamiento_img');
            $descripciones = $request->input('procesamiento_desc', []);

            foreach ($archivos as $index => $file) {
                if ($file && $file->isValid()) {
                    $path = $file->store("informes/{$informe->id}/procesamiento", 'public');
                    
                    Imagen::create([
                        'informe_id' => $informe->id,
                        'fase' => 'procesamiento',
                        'ruta' => $path,
                        'descripcion' => $descripciones[$index] ?? null,
                    ]);
                    Log::info("Imagen procesamiento guardada en: $path");
                }
            }
        }

        // 3. Tinción
        if ($request->hasFile('tincion_img')) {
            $archivos = $request->file('tincion_img');
            $descripciones = $request->input('tincion_desc', []);

            foreach ($archivos as $index => $file) {
                if ($file && $file->isValid()) {
                    $path = $file->store("informes/{$informe->id}/tincion", 'public');
                    
                    Imagen::create([
                        'informe_id' => $informe->id,
                        'fase' => 'tincion',
                        'ruta' => $path,
                        'descripcion' => $descripciones[$index] ?? null,
                    ]);
                    Log::info("Imagen tinción guardada en: $path");
                }
            }
        }

        // 4. Micro Obligatorias
        if ($request->hasFile('micros_required_img')) {
            $archivos = $request->file('micros_required_img');
            $descripciones = $request->input('micros_required_desc', []);
            
            foreach ($archivos as $zoom => $file) {
                 if ($file && $file->isValid()) {
                    $path = $file->store("informes/{$informe->id}/microscopio", 'public');
                    
                    Imagen::updateOrCreate(
                        [
                            'informe_id' => $informe->id,
                            'fase' => 'microscopio',
                            'zoom' => $zoom,
                            'obligatoria' => true
                        ],
                        [
                            'ruta' => $path,
                            'descripcion' => $descripciones[$zoom] ?? null
                        ]
                    );
                 }
            }
        }
        
        // 3. Micro Extras
        if ($request->hasFile('micros_extra_img')) {
             $archivos = $request->file('micros_extra_img');
             $descripciones = $request->input('micros_extra_desc', []);
             $zooms = $request->input('micros_extra_zoom', []);

             foreach ($archivos as $index => $file) {
                if ($file && $file->isValid()) {
                    $path = $file->store("informes/{$informe->id}/microscopio", 'public');
                    
                    Imagen::create([
                        'informe_id' => $informe->id,
                        'fase' => 'microscopio',
                        'ruta' => $path,
                        'zoom' => $zooms[$index] ?? null,
                        'descripcion' => $descripciones[$index] ?? null,
                        'obligatoria' => false
                    ]);
                }
             }
        }
    }
}
