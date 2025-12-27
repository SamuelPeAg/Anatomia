<?php

namespace App\Http\Controllers;

use App\Models\TipoMuestra;
use App\Models\Informe;
use Illuminate\Http\JsonResponse;

class TipoMuestraController extends Controller
{
    /**
     * Devuelve el siguiente código identificador para un tipo de muestra
     * Ej: B2530 (prefijo + año(2) + siguiente correlativo)
     *
     * GET /tipos/{tipo}/siguiente-codigo
     */
    public function siguienteCodigo(TipoMuestra $tipo): JsonResponse
    {
        $anio = (int) now()->format('Y');  // 2025
        $yy   = now()->format('y');        // 25

        // Último correlativo usado para ESTE tipo y ESTE año
        $ultimo = Informe::where('tipo_id', $tipo->id)
            ->where('anio', $anio)
            ->max('correlativo');

        $siguiente = ($ultimo ?? 0) + 1;

        // Formato compacto: B2530
        $codigo = $tipo->prefijo . $yy . $siguiente;

        return response()->json([
            'tipo_id' => $tipo->id,
            'prefijo' => $tipo->prefijo,
            'anio' => $anio,
            'correlativo' => $siguiente,
            'codigo' => $codigo,
        ]);
    }
}
