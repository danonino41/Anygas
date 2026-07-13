<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'clientes';
    public $timestamps = false;

    protected $fillable = [
        'documento_identidad',
        'nombres',
        'apellidos',
        'telefono',
        'direccion_principal',
        'referencia_direccion',
        'correo',
        'estado',
        'deuda_envases',
        'notas_internas'
    ];

    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'cliente_id');
    }

    public function direcciones()
    {
        return $this->hasMany(ClienteDireccion::class, 'cliente_id')->orderBy('es_principal', 'desc');
    }

    public function telefonos()
    {
        return $this->hasMany(ClienteTelefono::class, 'cliente_id')->orderBy('es_principal', 'desc');
    }

    public function getNombreCompletoAttribute()
    {
        return trim(($this->nombres ?? '') . ' ' . ($this->apellidos ?? ''));
    }
}