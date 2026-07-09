<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Producto extends Model
{
    protected $table = 'productos';
    public $timestamps = false;

    protected $fillable = [
        'proveedor_id',
        'nombre',
        'marca',
        'imagen',
        'descripcion',
        'tipo_entrada',
        'precio_venta',
        'precio_compra',
        'stock_actual',
        'estado'
    ];

    protected $appends = ['imagen_url'];

    public function getImagenUrlAttribute(): string
    {
        if ($this->imagen) {
            return Storage::url($this->imagen);
        }

        $nombre = $this->nombre ?? 'Producto';
        $nombreLower = strtolower($nombre);
        if (str_contains($nombreLower, 'balón') || str_contains($nombreLower, '45')) {
            $emoji = '🛢️';
        } elseif (str_contains($nombreLower, 'manguera')) {
            $emoji = '〰️';
        } else {
            $emoji = '🔧';
        }

        $colors = match ($this->tipo_entrada) {
            'premium' => ['from' => '#8B5CF6', 'to' => '#6D28D9'],
            'ninguna' => ['from' => '#6B7280', 'to' => '#4B5563'],
            default   => ['from' => '#F59E0B', 'to' => '#D97706'],
        };

        $inicial = mb_strtoupper(mb_substr($nombre, 0, 2));

        $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 400">
  <defs><linearGradient id="g" x1="0%" y1="0%" x2="100%" y2="100%">
    <stop offset="0%" style="stop-color:{$colors['from']}"/>
    <stop offset="100%" style="stop-color:{$colors['to']}"/>
  </linearGradient></defs>
  <rect width="400" height="400" rx="40" fill="url(#g)"/>
  <text x="200" y="180" font-family="Arial" font-size="120" font-weight="700" fill="white" text-anchor="middle" dominant-baseline="central" opacity="0.9">{$inicial}</text>
  <text x="200" y="280" font-family="Arial" font-size="48" fill="white" text-anchor="middle" dominant-baseline="central" opacity="0.8">{$emoji}</text>
</svg>
SVG;

        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'proveedor_id');
    }

    public function detallesPedido()
    {
        return $this->hasMany(DetallePedido::class, 'producto_id');
    }

    public function detallesReabastecimiento()
    {
        return $this->hasMany(DetalleReabastecimiento::class, 'producto_id');
    }
}