<?php

namespace App\Jobs;

use App\Models\Comprobante;
use App\Services\SunatService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class EnviarComprobanteSunat implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;

    public $backoff = [30, 120];

    public function __construct(
        public Comprobante $comprobante
    ) {}

    public function handle(): void
    {
        $sunat = new SunatService();
        $resultado = $sunat->enviarComprobante($this->comprobante->pedido);

        if (!$resultado['success']) {
            throw new \RuntimeException($resultado['message'] ?? 'Error al enviar a SUNAT');
        }
    }

    public function failed(\Throwable $e): void
    {
        $this->comprobante->update([
            'estado_sincronizacion' => 'pendiente',
        ]);
    }
}
