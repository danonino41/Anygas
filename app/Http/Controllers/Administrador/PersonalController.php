<?php

namespace App\Http\Controllers\Administrador;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class PersonalController extends Controller
{
    public function index()
    {
        $usuarios = Usuario::orderBy('nombre_completo')->get();

        $total = $usuarios->count();
        $activos = $usuarios->where('estado', 'activo')->count();
        $recepcionistas = $usuarios->where('rol', 'recepcionista')->count();
        $motorizados = $usuarios->where('rol', 'motorizado')->count();

        return view('administrador.personal', compact(
            'usuarios', 'total', 'activos', 'recepcionistas', 'motorizados'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'documento_identidad' => 'required|string|max:15|unique:usuarios,documento_identidad',
            'nombre_completo' => 'required|string|max:100',
            'correo' => 'required|email|max:100|unique:usuarios,correo',
            'telefono' => 'required|string|max:15',
            'rol' => 'required|in:administrador,recepcionista,motorizado',
            'contrasena' => 'required|string|min:6',
        ]);

        $validated['contrasena'] = Hash::make($validated['contrasena']);
        $validated['estado'] = 'activo';

        Usuario::create($validated);

        return redirect()->route('admin.personal')
            ->with('success', 'Usuario creado correctamente.');
    }

    public function update(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);

        $validated = $request->validate([
            'documento_identidad' => ['required', 'string', 'max:15', Rule::unique('usuarios', 'documento_identidad')->ignore($id)],
            'nombre_completo' => 'required|string|max:100',
            'correo' => ['required', 'email', 'max:100', Rule::unique('usuarios', 'correo')->ignore($id)],
            'telefono' => 'required|string|max:15',
            'rol' => 'required|in:administrador,recepcionista,motorizado',
        ]);

        $usuario->update($validated);

        return redirect()->route('admin.personal')
            ->with('success', 'Usuario actualizado correctamente.');
    }

    public function updatePassword(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);

        $validated = $request->validate([
            'contrasena' => 'required|string|min:6',
        ]);

        $usuario->update([
            'contrasena' => Hash::make($validated['contrasena']),
        ]);

        return redirect()->route('admin.personal')
            ->with('success', 'Contraseña actualizada correctamente.');
    }

    public function toggleEstado($id)
    {
        $usuario = Usuario::findOrFail($id);

        $nuevoEstado = match ($usuario->estado) {
            'activo' => 'inactivo',
            'inactivo' => 'activo',
            'suspendido' => 'activo',
            default => 'activo',
        };

        $usuario->update(['estado' => $nuevoEstado]);

        return redirect()->route('admin.personal')
            ->with('success', "Usuario {$usuario->nombre_completo} ahora está {$nuevoEstado}.");
    }
}
