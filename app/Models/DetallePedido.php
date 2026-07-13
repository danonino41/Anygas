<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetallePedido extends Model
{
    protected $table = 'detalles_pedido';
    public $timestamps = false;

    protected $fillable = [
        'pedido_id',
        'producto_id',
        'cantidad',
        'precio_unitario',
        'subtotal',
        'envases_devueltos'
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'pedido_id');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }
}