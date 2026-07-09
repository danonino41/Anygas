<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClienteDireccion extends Model
{
    protected $table = 'cliente_direcciones';

    protected $fillable = [
        'cliente_id',
        'direccion',
        'referencia',
        'latitud',
        'longitud',
        'etiqueta',
        'es_principal',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }
}
