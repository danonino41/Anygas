<?php

namespace App\Http\Controllers\Administrador;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProductosController extends Controller
{
    public function index(Request $request)
    {
        $query = Producto::with('proveedor');

        if ($buscar = $request->get('buscar')) {
            $query->where(function ($q) use ($buscar) {
                $q->where('nombre', 'like', "%{$buscar}%")
                  ->orWhere('marca', 'like', "%{$buscar}%");
            });
        }

        if ($proveedorFiltro = $request->get('proveedor')) {
            $query->where('proveedor_id', $proveedorFiltro);
        }

        if ($tipoFiltro = $request->get('tipo')) {
            $query->where('tipo_entrada', $tipoFiltro);
        }

        if ($estadoFiltro = $request->get('estado')) {
            $query->where('estado', $estadoFiltro);
        }

        $productos = $query->orderBy('nombre')->paginate(10)->withQueryString()->onEachSide(0);
        $proveedores = Proveedor::where('estado', 'activo')->orderBy('nombre_empresa')->get();

        $total = Producto::count();
        $stockTotal = Producto::sum('stock_actual');
        $disponibles = Producto::where('estado', 'disponible')->count();
        $bajoStock = Producto::where('stock_actual', '<=', 5)->count();

        $tiposMap = ['estandar' => 'Estándar', 'premium' => 'Premium', 'ninguna' => 'Genérico'];
        $tipoBadgeMap = ['estandar' => 'bg-warning text-dark', 'premium' => 'bg-primary', 'ninguna' => 'bg-secondary'];
        $estadoLabelMap = ['disponible' => 'Disponible', 'agotado' => 'Agotado', 'descontinuado' => 'Descontinuado'];
        $estadoBadgeMap = ['disponible' => 'bg-success', 'agotado' => 'bg-danger', 'descontinuado' => 'bg-secondary'];

        $productos->getCollection()->transform(function ($p) use ($tiposMap, $tipoBadgeMap, $estadoLabelMap, $estadoBadgeMap) {
            $p->tipo_label = $tiposMap[$p->tipo_entrada] ?? $p->tipo_entrada;
            $p->tipo_badge = $tipoBadgeMap[$p->tipo_entrada] ?? 'bg-secondary';
            $p->estado_label = $estadoLabelMap[$p->estado] ?? $p->estado;
            $p->estado_badge = $estadoBadgeMap[$p->estado] ?? 'bg-secondary';

            if ($p->stock_actual > 5) {
                $p->stock_badge = 'bg-success';
            } elseif ($p->stock_actual > 0) {
                $p->stock_badge = 'bg-warning text-dark';
            } else {
                $p->stock_badge = 'bg-danger';
            }

            $p->toggle_accion = $p->estado === 'disponible' ? 'agotar' : 'disponible';
            $p->toggle_label = $p->estado === 'disponible' ? 'Desactivar' : 'Activar';
            $p->toggle_icon = $p->estado === 'disponible' ? 'pause-fill' : 'play-fill';
            $p->toggle_btn = $p->estado === 'disponible' ? 'btn-outline-secondary' : 'btn-outline-success';

            return $p;
        });

        return view('administrador.productos', compact(
            'productos', 'proveedores', 'total', 'stockTotal', 'disponibles', 'bajoStock'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'marca' => 'required|string|max:50',
            'proveedor_id' => 'required|exists:proveedores,id',
            'tipo_entrada' => 'required|in:estandar,premium,ninguna',
            'precio_compra' => 'nullable|numeric|min:0',
            'precio_venta' => 'required|numeric|min:0',
            'stock_actual' => 'required|integer|min:0',
            'descripcion' => 'nullable|string|max:500',
        ]);

        $validated['estado'] = $validated['stock_actual'] > 0 ? 'disponible' : 'agotado';

        Producto::create($validated);

        return redirect()->route('admin.productos')
            ->with('success', 'Producto creado correctamente.');
    }

    public function update(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'marca' => 'required|string|max:50',
            'proveedor_id' => 'required|exists:proveedores,id',
            'tipo_entrada' => 'required|in:estandar,premium,ninguna',
            'precio_compra' => 'nullable|numeric|min:0',
            'precio_venta' => 'required|numeric|min:0',
            'stock_actual' => 'required|integer|min:0',
            'descripcion' => 'nullable|string|max:500',
        ]);

        if ($request->has('imagen') && $request->imagen) {
            if ($producto->imagen) Storage::delete($producto->imagen);
            $validated['imagen'] = $request->file('imagen')->store('productos', 'public');
        }

        $producto->update($validated);

        return redirect()->route('admin.productos')
            ->with('success', 'Producto actualizado correctamente.');
    }

    public function toggleEstado($id)
    {
        $producto = Producto::findOrFail($id);

        $nuevoEstado = match ($producto->estado) {
            'disponible' => 'agotado',
            'agotado' => 'disponible',
            'descontinuado' => 'disponible',
            default => 'disponible',
        };

        $producto->update(['estado' => $nuevoEstado]);

        return redirect()->route('admin.productos')
            ->with('success', "{$producto->nombre} ahora está como «{$nuevoEstado}».");
    }

    public function updatePrice(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);

        $validated = $request->validate([
            'precio_venta' => 'required|numeric|min:0',
            'precio_compra' => 'nullable|numeric|min:0',
        ]);

        $producto->update($validated);

        return redirect()->route('admin.productos')
            ->with('success', "Precio de {$producto->nombre} actualizado.");
    }
}
