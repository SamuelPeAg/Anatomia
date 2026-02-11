<?php

namespace App\Http\Controllers;

use App\Models\Expediente;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ExpedienteController extends Controller
{
    /**
     * Muestra el formulario de acceso para pacientes.
     */
    public function showAcceso()
    {
        return view('paciente.login');
    }

    /**
     * Valida el email y guarda en sesión para "autenticar" al paciente.
     */
    public function acceder(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:expedientes,correo',
        ], [
            'email.exists' => 'No se ha encontrado ninguna historia asociada a este correo.',
        ]);

        session(['paciente_email' => $request->email]);

        return redirect()->route('paciente.informes')
            ->with('success', 'Bienvenido a su historial de informes.');
    }

    /**
     * Lista los informes asociados al expediente del paciente en sesión.
     */
    public function misInformes()
    {
        $email = session('paciente_email');

        if (!$email) {
            return redirect()->route('paciente.acceso')
                ->with('error', 'Debe identificarse para ver sus informes.');
        }

        $expediente = Expediente::with('informes.tipo')
            ->where('correo', $email)
            ->first();

        if (!$expediente) {
            return redirect()->route('paciente.acceso')
                ->with('error', 'Expediente no encontrado.');
        }

        $informes = $expediente->informes()->orderBy('created_at', 'desc')->get();

        return view('paciente.informes', compact('expediente', 'informes'));
    }
    /**
     * [ADMIN] Muestra el listado de todos los expedientes registrados.
     */
    public function index()
    {
        // Obtener expedientes que tengan al menos un informe, ordenados por recientes
        $expedientes = Expediente::withCount('informes')
            ->orderBy('updated_at', 'desc')
            ->paginate(12);

        return view('expedientes.index', compact('expedientes'));
    }

    /**
     * [ADMIN] Muestra el detalle de un expediente y sus informes.
     */
    public function show(Expediente $expediente)
    {
        $expediente->load(['informes' => function($q) {
            $q->orderBy('created_at', 'desc');
        }, 'informes.tipo']);
        
        return view('expedientes.show', compact('expediente'));
    }
    /**
     * [API] Busca expedientes por nombre para el autocompletado via AJAX.
     */
    public function search(Request $request)
    {
        $term = $request->get('term');
        
        if (strlen($term) < 2) {
            return response()->json([]);
        }

        $expedientes = Expediente::where('nombre', 'LIKE', "%{$term}%")
            ->limit(10)
            ->get(['nombre', 'correo']); // Retornamos nombre y correo

        return response()->json($expedientes);
    }
}
