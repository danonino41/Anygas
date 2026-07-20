<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $table = 'audit_logs';
    public $timestamps = false;

    protected $fillable = [
        'usuario_id',
        'usuario_nombre',
        'accion',
        'modelo',
        'modelo_id',
        'datos_viejos',
        'datos_nuevos',
        'ip',
    ];

    protected $casts = [
        'datos_viejos' => 'array',
        'datos_nuevos' => 'array',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function getAccionLabelAttribute(): string
    {
        return match ($this->accion) {
            'created' => 'Creación',
            'updated' => 'Modificación',
            'deleted' => 'Eliminación',
            default => $this->accion,
        };
    }

    public function getAccionBadgeAttribute(): string
    {
        return match ($this->accion) {
            'created' => 'bg-success',
            'updated' => 'bg-warning text-dark',
            'deleted' => 'bg-danger',
            default => 'bg-secondary',
        };
    }

    public function getModeloLabelAttribute(): string
    {
        $partes = explode('\\', $this->modelo);
        return end($partes);
    }
}
