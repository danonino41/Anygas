<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reabastecimiento extends Model
{
    protected $table = 'reabastecimientos';
    public $timestamps = false;

    protected $fillable = [
        'proveedor_id',
        'usuario_id',
        'monto_total_compra',
        'fecha_compra'
    ];

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'proveedor_id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function detalles()
    {
        return $this->hasMany(DetalleReabastecimiento::class, 'reabastecimiento_id');
    }
}