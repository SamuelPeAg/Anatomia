<?php

namespace App\Http\Controllers;

use App\Models\Tipomuestra;
use App\Models\Informe;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TipoMuestraController extends Controller
{
    public function siguienteCodigo(string $prefijo)
    {
        $tipo = Tipomuestra::where('prefijo', $prefijo)->firstOrFail();
        
        $ultimo = Informe::where('tipo_id', $tipo->id)
            ->where('anio', now()->year)
            ->max('correlativo');

        $siguiente = ($ultimo ?? 0) + 1;
        $codigo = $tipo->prefijo . now()->format('y') . $siguiente;

        return response()->json([
            'tipo_id'     => $tipo->id,
            'prefijo'     => $tipo->prefijo,
            'correlativo' => $siguiente,
            'codigo'      => $codigo,
        ]);
    }
}
