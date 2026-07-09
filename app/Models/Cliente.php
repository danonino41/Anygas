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
        'estado'
    ];

    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'cliente_id');
    }

    public function direcciones()
    {
        return $this->hasMany(ClienteDireccion::class, 'cliente_id')->orderBy('es_principal', 'desc');
    }
}