<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PagoPedido extends Model
{
    protected $table = 'pagos_pedido';
    public $timestamps = false;

    protected $fillable = [
        'pedido_id',
        'tipo_pago_id',
        'monto',
        'monto_recibido'
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'pedido_id');
    }

    public function tipoPago()
    {
        return $this->belongsTo(TipoPago::class, 'tipo_pago_id');
    }
}