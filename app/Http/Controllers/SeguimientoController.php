<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use Illuminate\Http\Request;

class SeguimientoController extends Controller
{
    public function index()
    {
        return view('seguimiento');
    }

    public function consultar(Request $request)
    {
        $request->validate([
            'codigo' => 'required|string|max:20',
        ]);

        $pedido = Pedido::with([
            'cliente',
            'detalles.producto',
            'motorizado',
            'comprobante',
            'pagos.tipoPago',
        ])->where('codigo_seguimiento', strtoupper($request->codigo))
          ->first();

        if (!$pedido) {
            return back()->withErrors(['codigo' => 'No encontramos un pedido con ese código.']);
        }

        return view('seguimiento', compact('pedido'));
    }
}
