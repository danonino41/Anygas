<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'productos';
    public $timestamps = false;

    protected $fillable = [
        'proveedor_id',
        'nombre',
        'marca',
        'tipo_entrada',
        'precio_venta',
        'stock_actual',
        'estado'
    ];

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'proveedor_id');
    }

    public function detallesPedido()
    {
        return $this->hasMany(DetallePedido::class, 'producto_id');
    }

    public function detallesReabastecimiento()
    {
        return $this->hasMany(DetalleReabastecimiento::class, 'producto_id');
    }
}