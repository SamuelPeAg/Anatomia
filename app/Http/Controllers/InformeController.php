<?php

namespace App\Http\Controllers;

use App\Models\Informe;
use App\Models\TipoMuestra;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class InformeController extends Controller
{
    public function index(): View
    {
        $informes = Informe::with('tipo')->orderBy('created_at', 'desc')->get();

        foreach ($informes as $informe) {
            $faseInfo = $this->getSiguienteFaseInfo($informe);
            $informe->siguiente_fase = $faseInfo['nombre'];
            $informe->fase_n = $faseInfo['numero'];
        }

        return view('revision', compact('informes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'tipo_muestra' => 'required|string',
            'codigo_identificador' => 'required|string|unique:informes,codigo_identificador',
            'observaciones_llegada' => 'required|string',
        ]);

        $tipo = TipoMuestra::where('prefijo', $request->tipo_muestra)->firstOrFail();
        
        $informe = Informe::create([
            'tipo_id' => $tipo->id,
            'anio' => now()->year,
            'correlativo' => (int) substr($request->codigo_identificador, strlen($tipo->prefijo) + 2),
            'codigo_identificador' => $request->codigo_identificador,
            'estado' => 'incompleto',
            'recepcion_observaciones' => $request->observaciones_llegada,
            'recepcion_organo' => $request->organo,
        ]);

        return redirect()->route('informes.edit', $informe)
            ->with('success', 'Recepción guardada correctamente.');
    }

    public function edit(Informe $informe): View
    {
        $numeroFase = request('fase') ?: $this->getSiguienteFaseInfo($informe)['numero'];
        
        return view('nuevoinforme', compact('informe', 'numeroFase'));
    }

    public function update(Request $request, Informe $informe): RedirectResponse
    {
        $data = [];

        // Mapeo de campos por fase detectada en el request
        if ($request->has('observaciones_llegada')) {
            $data['recepcion_observaciones'] = $request->observaciones_llegada;
            $data['recepcion_organo'] = $request->organo;
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

        if ($request->input('stay') == '1') {
            return back()->with('success', 'Progreso guardado.');
        }

        $next = $this->getSiguienteFaseInfo($informe);
        return redirect()->route('informes.edit', ['informe' => $informe, 'fase' => $next['numero']])
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
}
