<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClienteTelefono extends Model
{
    protected $table = 'cliente_telefonos';

    protected $fillable = [
        'cliente_id',
        'telefono',
        'etiqueta',
        'es_principal',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }
}
