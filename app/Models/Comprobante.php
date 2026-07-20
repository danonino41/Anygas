<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;

class Comprobante extends Model
{
    use Auditable;
    protected $table = 'comprobantes';
    public $timestamps = false;

    protected $fillable = [
        'pedido_id',
        'tipo_comprobante',
        'serie',
        'numero_correlativo',
        'fecha_emision',
        'estado_sincronizacion',
        'monto_total',
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'pedido_id');
    }

    public function getSerieCorrelativoAttribute(): string
    {
        return "{$this->serie}-{$this->numero_correlativo}";
    }

    public function getBaseImponibleAttribute(): float
    {
        $total = $this->monto_total;
        if ($this->tipo_comprobante === 'factura') {
            return round($total / 1.18, 2);
        }
        return $total;
    }

    public function getIgvAttribute(): float
    {
        if ($this->tipo_comprobante === 'factura') {
            return round($this->monto_total - $this->base_imponible, 2);
        }
        return 0.00;
    }

    public function getMontoTotalAttribute(): float
    {
        return (float) ($this->attributes['monto_total'] ?? $this->pedido->monto_total);
    }

    public function getEstadoBadgeAttribute(): string
    {
        return match ($this->estado_sincronizacion) {
            'aceptado' => 'bg-success',
            'pendiente' => 'bg-warning text-dark',
            'rechazado' => 'bg-danger',
            default => 'bg-secondary',
        };
    }

    public function getEstadoLabelAttribute(): string
    {
        return match ($this->estado_sincronizacion) {
            'aceptado' => 'Aceptado',
            'pendiente' => 'Pendiente',
            'rechazado' => 'Rechazado',
            default => $this->estado_sincronizacion,
        };
    }

    public function getTipoLabelAttribute(): string
    {
        return match ($this->tipo_comprobante) {
            'boleta' => 'Boleta de Venta',
            'factura' => 'Factura',
            'ticket' => 'Ticket Interno',
            default => $this->tipo_comprobante,
        };
    }

    public function scopeFiltrar($query, \Illuminate\Http\Request $request)
    {
        if ($desde = $request->get('desde')) {
            $query->whereDate('fecha_emision', '>=', $desde);
        }
        if ($hasta = $request->get('hasta')) {
            $query->whereDate('fecha_emision', '<=', $hasta);
        }
        if ($tipo = $request->get('tipo')) {
            $query->where('tipo_comprobante', $tipo);
        }
        if ($estado = $request->get('estado_sinc')) {
            $query->where('estado_sincronizacion', $estado);
        }
        if ($buscar = $request->get('buscar')) {
            $query->where(function ($q) use ($buscar) {
                $q->whereRaw("CONCAT(serie, '-', numero_correlativo) LIKE ?", ["%{$buscar}%"])
                  ->orWhereHas('pedido.cliente', function ($cq) use ($buscar) {
                      $cq->where('nombres', 'like', "%{$buscar}%")
                         ->orWhere('apellidos', 'like', "%{$buscar}%")
                         ->orWhere('documento_identidad', 'like', "%{$buscar}%");
                  });
            });
        }
    }
}
