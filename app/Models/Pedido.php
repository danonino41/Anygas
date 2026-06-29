<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $table = 'pedidos';
    public $timestamps = false;

    protected $fillable = [
        'codigo_seguimiento',
        'cliente_id',
        'recepcionista_id',
        'motorizado_id',
        'direccion_entrega',
        'referencia_entrega',
        'tipo_despacho',
        'monto_total',
        'estado',
        'fecha_registro',
        'fecha_entrega'
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function recepcionista()
    {
        return $this->belongsTo(Usuario::class, 'recepcionista_id');
    }

    public function motorizado()
    {
        return $this->belongsTo(Usuario::class, 'motorizado_id');
    }

    public function detalles()
    {
        return $this->hasMany(DetallePedido::class, 'pedido_id');
    }

    public function pagos()
    {
        return $this->hasMany(PagoPedido::class, 'pedido_id');
    }

    public function comprobante()
    {
        return $this->hasOne(Comprobante::class, 'pedido_id');
    }
}