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
    public function showAcceso(): View
    {
        return view('paciente.login');
    }

    /**
     * Valida el email y guarda en sesión para "autenticar" al paciente.
     */
    public function acceder(Request $request): RedirectResponse
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
    public function misInformes(): View|RedirectResponse
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
}
