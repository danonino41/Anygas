<?php

namespace App\Console\Commands;

use App\Models\Producto;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class GenerarPlaceholdersProductos extends Command
{
    protected $signature = 'productos:generar-placeholders';
    protected $description = 'Genera imágenes placeholder SVG para productos sin imagen';

    public function handle()
    {
        $productos = Producto::whereNull('imagen')->get();

        if ($productos->isEmpty()) {
            $this->info('Todos los productos ya tienen imagen.');
            return;
        }

        $this->info("Generando placeholders para {$productos->count()} productos...");

        Storage::makeDirectory('public/productos');

        foreach ($productos as $prod) {
            $inicial = mb_strtoupper(mb_substr($prod->nombre, 0, 2));

            $paleta = $this->getPaleta($prod->tipo_entrada, $prod->nombre);

            $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 400">
  <defs>
    <linearGradient id="bg" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" style="stop-color:{$paleta['gradient_start']}"/>
      <stop offset="100%" style="stop-color:{$paleta['gradient_end']}"/>
    </linearGradient>
  </defs>
  <rect width="400" height="400" rx="40" fill="url(#bg)"/>
  <text x="200" y="180" font-family="Inter, Arial, sans-serif" font-size="120" font-weight="700" fill="white" text-anchor="middle" dominant-baseline="central" opacity="0.9">{$inicial}</text>
  <text x="200" y="270" font-family="Inter, Arial, sans-serif" font-size="24" font-weight="400" fill="white" text-anchor="middle" dominant-baseline="central" opacity="0.8">{$paleta['label']}</text>
</svg>
SVG;

            $filename = "productos/placeholder_{$prod->id}.svg";
            Storage::disk('public')->put($filename, $svg);

            $prod->imagen = $filename;
            $prod->save();

            $this->line("  ✓ {$prod->nombre}");
        }

        $this->newLine();
        $this->info('Placeholders generados correctamente.');
    }

    private function getPaleta(string $tipoEntrada, string $nombre): array
    {
        $nombreLower = strtolower($nombre);

        if (str_contains($nombreLower, 'balón') || str_contains($nombreLower, '45')) {
            return [
                'gradient_start' => '#F59E0B',
                'gradient_end'   => '#D97706',
                'label'          => 'GLP',
            ];
        }

        if (str_contains($nombreLower, 'manguera')) {
            return [
                'gradient_start' => '#0EA5E9',
                'gradient_end'   => '#0284C7',
                'label'          => 'Manguera',
            ];
        }

        if ($tipoEntrada === 'premium') {
            return [
                'gradient_start' => '#8B5CF6',
                'gradient_end'   => '#6D28D9',
                'label'          => 'Premium',
            ];
        }

        if ($tipoEntrada === 'ninguna') {
            return [
                'gradient_start' => '#6B7280',
                'gradient_end'   => '#4B5563',
                'label'          => 'Accesorio',
            ];
        }

        return [
            'gradient_start' => '#10B981',
            'gradient_end'   => '#059669',
            'label'          => 'Estándar',
        ];
    }
}
