<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuarios';
    public $timestamps = false;

    protected $fillable = [
        'documento_identidad',
        'nombre_completo',
        'correo',
        'contrasena',
        'telefono',
        'rol',
        'estado'
    ];

    protected $hidden = [
        'contrasena',
    ];

    public function getAuthPassword()
    {
        return $this->contrasena;
    }

    public function pedidosRecepcionados()
    {
        return $this->hasMany(Pedido::class, 'recepcionista_id');
    }

    public function pedidosMotorizados()
    {
        return $this->hasMany(Pedido::class, 'motorizado_id');
    }

    public function reabastecimientos()
    {
        return $this->hasMany(Reabastecimiento::class, 'usuario_id');
    }
}