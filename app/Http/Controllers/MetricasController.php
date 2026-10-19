<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use Illuminate\Support\Facades\Gate;

class MetricasController extends Controller
{
    public function index()
    {
        Gate::authorize('ver-metricas'); // 403 si no es admin

        return response()->json([
            'total_eventos' => Evento::count(),
            'monto_recaudado' => 0, // no hay tabla de pagos, se deja en 0
        ], 200);
    }
}
