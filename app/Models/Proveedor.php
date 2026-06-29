<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    protected $table = 'proveedores';
    public $timestamps = false;

    protected $fillable = [
        'nombre_empresa',
        'ruc',
        'telefono',
        'nombre_contacto',
        'estado'
    ];

    public function productos()
    {
        return $this->hasMany(Producto::class, 'proveedor_id');
    }

    public function reabastecimientos()
    {
        return $this->hasMany(Reabastecimiento::class, 'proveedor_id');
    }
}