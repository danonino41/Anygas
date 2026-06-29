<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comprobante extends Model
{
    protected $table = 'comprobantes';
    public $timestamps = false;

    protected $fillable = [
        'pedido_id',
        'tipo_comprobante',
        'serie',
        'numero_correlativo',
        'fecha_emision'
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'pedido_id');
    }
}