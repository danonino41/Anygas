<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route($this->obtenerRutaPorRol(Auth::user()->rol));
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'correo' => ['required', 'email'],
            'contrasena' => ['required'],
        ], [
            'correo.required' => 'El correo electrónico es obligatorio.',
            'contrasena.required' => 'La contraseña es obligatoria.',
        ]);

        // Mapeamos el campo 'contrasena' de tu formulario al estándar 'password' de Laravel
        $intentarAuth = [
            'correo' => $credentials['correo'],
            'password' => $credentials['contrasena'],
            'estado' => 'activo'
        ];

        if (Auth::attempt($intentarAuth)) {
            $request->session()->regenerate();
            
            return redirect()->route($this->obtenerRutaPorRol(Auth::user()->rol));
        }

        return back()->withErrors([
            'error_login' => 'Las credenciales proporcionadas no coinciden con nuestros registros o tu usuario está inactivo.',
        ])->onlyInput('correo');
    }

    private function obtenerRutaPorRol($rol)
    {
        $mapeoRoles = [
            'administrador' => 'admin.dashboard',          
            'recepcionista' => 'recepcionista.dashboard',        
            'motorizado'    => 'motorizado.dashboard',        
        ];

        return $mapeoRoles[$rol] ?? 'login';
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login');
    }
}