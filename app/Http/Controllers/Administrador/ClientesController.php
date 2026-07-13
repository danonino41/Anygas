<?php

namespace App\Http\Controllers\Administrador;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\ClienteDireccion;
use App\Models\ClienteTelefono;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClientesController extends Controller
{
    public function index(Request $request)
    {
        $query = Cliente::select('clientes.*')
            ->selectSub(function ($q) {
                $q->from('pedidos')
                    ->selectRaw('COALESCE(SUM(monto_total), 0)')
                    ->whereColumn('cliente_id', 'clientes.id');
            }, 'total_gastado');
        $query->withCount('pedidos as cantidad_pedidos');

        if ($buscar = $request->get('buscar')) {
            $query->where(function ($q) use ($buscar) {
                $q->where('nombres', 'like', "%{$buscar}%")
                  ->orWhere('apellidos', 'like', "%{$buscar}%")
                  ->orWhere('documento_identidad', 'like', "%{$buscar}%")
                  ->orWhere('telefono', 'like', "%{$buscar}%");
            });
        }

        if ($estadoFiltro = $request->get('estado')) {
            $query->where('estado', $estadoFiltro);
        }

        $clientes = $query->orderBy('nombres')->paginate(10)->withQueryString()->onEachSide(0);

        $total = Cliente::count();
        $activos = Cliente::where('estado', 'activo')->count();
        $morosos = Cliente::where('estado', 'moroso')->count();
        $deudaEnvases = Cliente::sum('deuda_envases');

        $estadoLabelMap = ['activo' => 'Activo', 'inactivo' => 'Inactivo', 'moroso' => 'Moroso'];
        $estadoBadgeMap = ['activo' => 'bg-success', 'inactivo' => 'bg-secondary', 'moroso' => 'bg-danger'];

        $clientes->getCollection()->transform(function ($c) use ($estadoLabelMap, $estadoBadgeMap) {
            $c->estado_label = $estadoLabelMap[$c->estado] ?? $c->estado;
            $c->estado_badge = $estadoBadgeMap[$c->estado] ?? 'bg-secondary';
            $c->nombre_completo = trim("{$c->nombres} {$c->apellidos}");

            $c->toggle_accion = $c->estado === 'activo' ? 'inactivar' : ($c->estado === 'moroso' ? 'activar' : 'activar');
            $c->toggle_label = $c->estado === 'activo' ? 'Desactivar' : 'Activar';
            $c->toggle_icon = $c->estado === 'activo' ? 'pause-fill' : 'play-fill';
            $c->toggle_btn = $c->estado === 'activo' ? 'btn-outline-secondary' : 'btn-outline-success';

            return $c;
        });

        return view('administrador.clientes', compact(
            'clientes', 'total', 'activos', 'morosos', 'deudaEnvases'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'documento_identidad' => 'nullable|string|max:20',
            'nombres' => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'telefono' => 'required|string|max:15|unique:clientes,telefono',
            'direccion_principal' => 'nullable|string|max:255',
            'referencia_direccion' => 'nullable|string|max:255',
            'correo' => 'nullable|email|max:100',
            'deuda_envases' => 'nullable|integer|min:0',
            'notas_internas' => 'nullable|string|max:500',
        ]);

        $validated['estado'] = 'activo';
        $validated['deuda_envases'] = $validated['deuda_envases'] ?? 0;

        $cliente = Cliente::create($validated);

        return redirect()->route('admin.clientes')
            ->with('success', "Cliente {$cliente->nombre_completo} creado correctamente.");
    }

    public function show($id)
    {
        $cliente = Cliente::with(['direcciones', 'telefonos', 'pedidos.detalles', 'pedidos.pagos.tipoPago'])
            ->withCount('pedidos as cantidad_pedidos')
            ->findOrFail($id);

        $cliente->total_gastado = $cliente->pedidos->sum('monto_total');

        $cliente->nombre_completo = trim("{$cliente->nombres} {$cliente->apellidos}");

        $estadoLabelMap = ['activo' => 'Activo', 'inactivo' => 'Inactivo', 'moroso' => 'Moroso'];
        $estadoBadgeMap = ['activo' => 'bg-success', 'inactivo' => 'bg-secondary', 'moroso' => 'bg-danger'];
        $cliente->estado_label = $estadoLabelMap[$cliente->estado] ?? $cliente->estado;
        $cliente->estado_badge = $estadoBadgeMap[$cliente->estado] ?? 'bg-secondary';

        $cliente->ultimo_pedido = $cliente->pedidos->sortByDesc('fecha_registro')->first();
        $cliente->pedidos_30dias = $cliente->pedidos->where('fecha_registro', '>=', now()->subDays(30))->count();

        return response()->json($cliente);
    }

    public function ver($id)
    {
        $cliente = Cliente::with([
            'direcciones',
            'telefonos',
            'pedidos.detalles.producto',
            'pedidos.pagos.tipoPago',
            'pedidos.motorizado',
            'pedidos.recepcionista',
        ])->withCount('pedidos as cantidad_pedidos')
            ->findOrFail($id);

        $cliente->total_gastado = $cliente->pedidos->sum('monto_total');
        $cliente->nombre_completo = trim("{$cliente->nombres} {$cliente->apellidos}");

        $estadoLabelMap = ['activo' => 'Activo', 'inactivo' => 'Inactivo', 'moroso' => 'Moroso'];
        $estadoBadgeMap = ['activo' => 'bg-success', 'inactivo' => 'bg-secondary', 'moroso' => 'bg-danger'];
        $cliente->estado_label = $estadoLabelMap[$cliente->estado] ?? $cliente->estado;
        $cliente->estado_badge = $estadoBadgeMap[$cliente->estado] ?? 'bg-secondary';

        $cliente->ultimo_pedido = $cliente->pedidos->sortByDesc('fecha_registro')->first();
        $cliente->pedidos_30dias = $cliente->pedidos->where('fecha_registro', '>=', now()->subDays(30))->count();

        $pedidos = $cliente->pedidos->sortByDesc('fecha_registro');

        return view('administrador.cliente_detalle', compact('cliente', 'pedidos'));
    }

    public function update(Request $request, $id)
    {
        $cliente = Cliente::findOrFail($id);

        $validated = $request->validate([
            'documento_identidad' => 'nullable|string|max:20',
            'nombres' => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'telefono' => ['required', 'string', 'max:15', Rule::unique('clientes', 'telefono')->ignore($id)],
            'direccion_principal' => 'nullable|string|max:255',
            'referencia_direccion' => 'nullable|string|max:255',
            'correo' => 'nullable|email|max:100',
            'deuda_envases' => 'nullable|integer|min:0',
            'notas_internas' => 'nullable|string|max:500',
        ]);

        $validated['deuda_envases'] = $validated['deuda_envases'] ?? 0;

        $cliente->update($validated);

        return redirect()->route('admin.clientes')
            ->with('success', "Cliente {$cliente->nombre_completo} actualizado correctamente.");
    }

    public function toggleEstado($id)
    {
        $cliente = Cliente::findOrFail($id);

        $nuevoEstado = match ($cliente->estado) {
            'activo' => 'inactivo',
            'inactivo' => 'activo',
            'moroso' => 'activo',
            default => 'activo',
        };

        $cliente->update(['estado' => $nuevoEstado]);

        return redirect()->route('admin.clientes')
            ->with('success', "{$cliente->nombre_completo} ahora está como «{$nuevoEstado}».");
    }

    public function addTelefono(Request $request, $id)
    {
        $cliente = Cliente::findOrFail($id);

        $validated = $request->validate([
            'telefono' => 'required|string|max:15',
            'etiqueta' => 'nullable|string|max:50',
            'es_principal' => 'boolean',
        ]);

        $validated['cliente_id'] = $id;
        $validated['es_principal'] = $validated['es_principal'] ?? false;

        if ($validated['es_principal']) {
            ClienteTelefono::where('cliente_id', $id)->update(['es_principal' => false]);
        }

        ClienteTelefono::create($validated);

        return back()->with('success', 'Teléfono agregado correctamente.');
    }

    public function removeTelefono($id, $telefonoId)
    {
        $telefono = ClienteTelefono::where('cliente_id', $id)->findOrFail($telefonoId);
        $telefono->delete();

        return back()->with('success', 'Teléfono eliminado correctamente.');
    }

    public function addDireccion(Request $request, $id)
    {
        $cliente = Cliente::findOrFail($id);

        $validated = $request->validate([
            'direccion' => 'required|string|max:255',
            'referencia' => 'nullable|string|max:255',
            'latitud' => 'nullable|numeric|between:-90,90',
            'longitud' => 'nullable|numeric|between:-180,180',
            'etiqueta' => 'nullable|string|max:50',
            'es_principal' => 'boolean',
        ]);

        $validated['cliente_id'] = $id;
        $validated['es_principal'] = $validated['es_principal'] ?? false;

        if ($validated['es_principal']) {
            ClienteDireccion::where('cliente_id', $id)->update(['es_principal' => false]);
        }

        ClienteDireccion::create($validated);

        return back()->with('success', 'Dirección agregada correctamente.');
    }

    public function removeDireccion($id, $direccionId)
    {
        $direccion = ClienteDireccion::where('cliente_id', $id)->findOrFail($direccionId);
        $direccion->delete();

        return back()->with('success', 'Dirección eliminada correctamente.');
    }
}
