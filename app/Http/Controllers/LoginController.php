<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            return $this->redirigirPorRol(Auth::user()->rol);
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credenciales = $request->validate([
            'correo' => ['required', 'email'],
            'contrasena' => ['required'],
        ], [
            'correo.required' => 'El correo electrónico es obligatorio.',
            'correo.email' => 'Debes ingresar un formato de correo válido.',
            'contrasena.required' => 'La contraseña es obligatoria.',
        ]);

        $intentarAuth = [
            'correo' => $credenciales['correo'],
            'password' => $credenciales['contrasena'],
            'estado' => 'activo'
        ];

        if (Auth::attempt($intentarAuth)) {
            $request->session()->regenerate();

            return $this->redirigirPorRol(Auth::user()->rol);
        }

        return back()->withErrors([
            'error_login' => 'Las credenciales ingresadas no coinciden con nuestros registros o tu usuario está inactivo.',
        ])->onlyInput('correo');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    private function redirigirPorRol($rol)
    {
        return match ($rol) {
            'administrador' => redirect()->intended('/admin/dashboard'),
            'recepcionista' => redirect()->intended('/recepcionista/dashboard'),
            'motorizado'    => redirect()->intended('/motorizado/dashboard'),
            default         => redirect('/'),
        };
    }
}