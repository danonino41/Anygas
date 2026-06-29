<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleReabastecimiento extends Model
{
    protected $table = 'detalles_reabastecimiento';
    public $timestamps = false;

    protected $fillable = [
        'reabastecimiento_id',
        'producto_id',
        'cantidad_recibida',
        'costo_unitario_compra',
        'subtotal_compra'
    ];

    public function reabastecimiento()
    {
        return $this->belongsTo(Reabastecimiento::class, 'reabastecimiento_id');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }
}