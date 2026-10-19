<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // Requerido en Laravel 11 para usar $this->authorize()

class EventoController extends Controller
{
    use AuthorizesRequests; // Añade esto para habilitar el método $this->authorize

    public function index()
    {
        $eventos = Evento::with('organizador:id,name')->get();
        return response()->json($eventos, 200);
    }

    public function update(Request $request, $id)
    {
        $evento = Evento::findOrFail($id);

        $this->authorize('update', $evento); // usa la Policy → 403 si falla

        $datos = $request->validate([
            'titulo' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'cupo_maximo' => 'required|integer|min:1',
        ]);

        $evento->update($datos);

        return response()->json($evento, 200);
    }
}
