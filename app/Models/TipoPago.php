<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoPago extends Model
{
    protected $table = 'tipos_pago';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'estado'
    ];

    public function pagosPedido()
    {
        return $this->hasMany(PagoPedido::class, 'tipo_pago_id');
    }
}