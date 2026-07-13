<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ApiperuService
{
    private string $token;
    private string $baseUrl = 'https://apiperu.dev/api';

    public function __construct()
    {
        $this->token = config('services.apiperu.token', '');
    }

    public function consultarDni(string $dni): ?array
    {
        if (strlen($dni) !== 8 || !ctype_digit($dni)) {
            return null;
        }

        try {
            $response = Http::withoutVerifying()->withHeaders([
                'Accept' => 'application/json',
                'Authorization' => "Bearer {$this->token}",
            ])->timeout(10)->post("{$this->baseUrl}/dni", [
                'dni' => $dni,
            ]);

            if ($response->successful() && $response->json('success')) {
                $data = $response->json('data');
                return [
                    'nombres' => $data['nombre_completo'] ?? '',
                    'direccion' => $data['direccion_completa'] ?? $data['direccion'] ?? '',
                ];
            }

            return null;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function consultarRuc(string $ruc): ?array
    {
        if (strlen($ruc) !== 11 || !ctype_digit($ruc)) {
            return null;
        }

        try {
            $response = Http::withoutVerifying()->withHeaders([
                'Accept' => 'application/json',
                'Authorization' => "Bearer {$this->token}",
            ])->timeout(10)->post("{$this->baseUrl}/ruc", [
                'ruc' => $ruc,
            ]);

            if ($response->successful() && $response->json('success')) {
                $data = $response->json('data');
                return [
                    'razon_social' => $data['nombre_o_razon_social'] ?? '',
                    'direccion' => $data['direccion_completa'] ?? $data['direccion'] ?? '',
                ];
            }

            return null;
        } catch (\Exception $e) {
            return null;
        }
    }
}
