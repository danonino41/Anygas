<?php

namespace App\Http\Controllers\Recepcionista;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use App\Models\Proveedor;
use Illuminate\Http\Request;

class CatalogoController extends Controller
{
    public function index()
    {
        $productos = Producto::with('proveedor')
            ->orderBy('marca')
            ->orderBy('nombre')
            ->get();

        $marcas = Producto::select('marca')->distinct()->pluck('marca');

        $proveedores = Proveedor::where('estado', 'activo')
            ->orderBy('nombre_empresa')
            ->get();

        $precioMaximo = Producto::max('precio_venta');

        return view('recepcionista.catalogo', compact('productos', 'marcas', 'proveedores', 'precioMaximo'));
    }
}
