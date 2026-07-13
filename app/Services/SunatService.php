<?php

namespace App\Services;

use App\Models\Comprobante;
use App\Models\DetallePedido;
use App\Models\Pedido;
use Greenter\Model\Client\Client;
use Greenter\Model\Company\Address;
use Greenter\Model\Company\Company;
use Greenter\Model\Sale\FormaPagos\FormaPagoContado;
use Greenter\Model\Sale\Invoice;
use Greenter\Model\Sale\Legend;
use Greenter\Model\Sale\SaleDetail;
use Greenter\Model\Summary\Summary;
use Greenter\Model\Summary\SummaryDetail;
use Greenter\Model\Voided\Voided;
use Greenter\Model\Voided\VoidedDetail;
use Greenter\See;
use Greenter\Ws\Services\SunatEndpoints;

class SunatService
{
    private See $see;

    public function __construct()
    {
        $this->see = new See();

        $modo = config('sunat.modo');
        $this->see->setService(
            $modo === 'produccion'
                ? SunatEndpoints::FE_PRODUCCION
                : SunatEndpoints::FE_BETA
        );

        $certPath = storage_path(config('sunat.certificado_path'));
        if (file_exists($certPath)) {
            $this->see->setCertificate(file_get_contents($certPath));
        }

        $this->see->setClaveSOL(
            config('sunat.ruc'),
            config('sunat.clave_sol.usuario'),
            config('sunat.clave_sol.contrasena')
        );
    }

    public function getSee(): See
    {
        return $this->see;
    }

    private function buildCompany(): Company
    {
        $addr = config('sunat.direccion');

        $address = (new Address())
            ->setUbigueo($addr['ubigeo'])
            ->setDepartamento($addr['departamento'])
            ->setProvincia($addr['provincia'])
            ->setDistrito($addr['distrito'])
            ->setUrbanizacion($addr['urbanizacion'])
            ->setDireccion($addr['direccion'])
            ->setCodLocal($addr['cod_local']);

        return (new Company())
            ->setRuc(config('sunat.ruc'))
            ->setRazonSocial(config('sunat.razon_social'))
            ->setNombreComercial(config('sunat.nombre_comercial'))
            ->setAddress($address);
    }

    private function buildClient(Pedido $pedido): Client
    {
        $cliente = $pedido->cliente;
        $doc = $cliente->documento_identidad ?? '';
        $tipoDoc = strlen($doc) === 11 ? '6' : '1';

        return (new Client())
            ->setTipoDoc($tipoDoc)
            ->setNumDoc($doc)
            ->setRznSocial(trim("{$cliente->nombres} {$cliente->apellidos}"));
    }

    private function calcularBaseImponible(float $montoTotal): float
    {
        return round($montoTotal / 1.18, 2);
    }

    private function calcularIgv(float $montoTotal, float $baseImponible): float
    {
        return round($montoTotal - $baseImponible, 2);
    }

    private function montoEnLetras(float $monto): string
    {
        $entero = (int) floor($monto);
        $decimal = round(($monto - $entero) * 100);

        $unidades = ['', 'UN', 'DOS', 'TRES', 'CUATRO', 'CINCO', 'SEIS', 'SIETE', 'OCHO', 'NUEVE'];
        $especiales = ['DIEZ', 'ONCE', 'DOCE', 'TRECE', 'CATORCE', 'QUINCE', 'DIECISEIS', 'DIECISIETE', 'DIECIOCHO', 'DIECINUEVE'];
        $decenas = ['', 'DIEZ', 'VEINTE', 'TREINTA', 'CUARENTA', 'CINCUENTA', 'SESENTA', 'SETENTA', 'OCHENTA', 'NOVENTA'];
        $centenas = ['', 'CIENTO', 'DOSCIENTOS', 'TRESCIENTOS', 'CUATROCIENTOS', 'QUINIENTOS', 'SEISCIENTOS', 'SETECIENTOS', 'OCHOCIENTOS', 'NOVECIENTOS'];

        if ($entero === 0) {
            $texto = 'CERO';
        } elseif ($entero === 100) {
            $texto = 'CIEN';
        } else {
            $texto = '';
            $c = (int) ($entero / 100);
            $r = $entero % 100;

            if ($c > 0) {
                $texto .= $centenas[$c] . ' ';
            }

            if ($r > 0) {
                if ($r < 10) {
                    $texto .= $unidades[$r];
                } elseif ($r < 20) {
                    $texto .= $especiales[$r - 10];
                } else {
                    $d = (int) ($r / 10);
                    $u = $r % 10;
                    $texto .= $decenas[$d];
                    if ($u > 0) {
                        $texto .= ' Y ' . $unidades[$u];
                    }
                }
            }
        }

        return "SON {$texto} CON {$decimal}/100 SOLES";
    }

    public function crearFactura(Pedido $pedido): array
    {
        $montoTotal = (float) $pedido->monto_total;
        $baseImponible = $this->calcularBaseImponible($montoTotal);
        $igv = $this->calcularIgv($montoTotal, $baseImponible);

        $comprobante = $pedido->comprobante;
        if (!$comprobante) {
            throw new \RuntimeException('El pedido no tiene comprobante asociado.');
        }

        $invoice = (new Invoice())
            ->setUblVersion('2.1')
            ->setTipoOperacion('0101')
            ->setTipoDoc('01')
            ->setSerie($comprobante->serie)
            ->setCorrelativo($comprobante->numero_correlativo)
            ->setFechaEmision(new \DateTime($comprobante->fecha_emision . ' -05:00'))
            ->setFormaPago(new FormaPagoContado())
            ->setTipoMoneda('PEN')
            ->setCompany($this->buildCompany())
            ->setClient($this->buildClient($pedido))
            ->setMtoOperGravadas($baseImponible)
            ->setMtoIGV($igv)
            ->setTotalImpuestos($igv)
            ->setValorVenta($baseImponible)
            ->setSubTotal($montoTotal)
            ->setMtoImpVenta($montoTotal);

        $details = [];
        foreach ($pedido->detalles as $detalle) {
            $itemBase = round($detalle->subtotal / 1.18, 2);
            $itemIgv = round($detalle->subtotal - $itemBase, 2);

            $detail = (new SaleDetail())
                ->setCodProducto('P' . str_pad($detalle->producto_id, 5, '0', STR_PAD_LEFT))
                ->setUnidad('NIU')
                ->setCantidad($detalle->cantidad)
                ->setMtoValorUnitario(round($detalle->precio_unitario / 1.18, 2))
                ->setDescripcion($detalle->producto->nombre ?? 'Producto')
                ->setMtoBaseIgv($itemBase)
                ->setPorcentajeIgv(18.00)
                ->setIgv($itemIgv)
                ->setTipAfeIgv('10')
                ->setTotalImpuestos($itemIgv)
                ->setMtoValorVenta($itemBase)
                ->setMtoPrecioUnitario((float) $detalle->precio_unitario);

            $details[] = $detail;
        }

        $legend = (new Legend())
            ->setCode('1000')
            ->setValue($this->montoEnLetras($montoTotal));

        $invoice->setDetails($details)->setLegends([$legend]);

        return [
            'invoice' => $invoice,
            'base_imponible' => $baseImponible,
            'igv' => $igv,
            'monto_total' => $montoTotal,
        ];
    }

    public function crearBoleta(Pedido $pedido): array
    {
        $montoTotal = (float) $pedido->monto_total;

        $comprobante = $pedido->comprobante;
        if (!$comprobante) {
            throw new \RuntimeException('El pedido no tiene comprobante asociado.');
        }

        $invoice = (new Invoice())
            ->setUblVersion('2.1')
            ->setTipoOperacion('0101')
            ->setTipoDoc('03')
            ->setSerie($comprobante->serie)
            ->setCorrelativo($comprobante->numero_correlativo)
            ->setFechaEmision(new \DateTime($comprobante->fecha_emision . ' -05:00'))
            ->setFormaPago(new FormaPagoContado())
            ->setTipoMoneda('PEN')
            ->setCompany($this->buildCompany())
            ->setClient($this->buildClient($pedido))
            ->setMtoOperGravadas($montoTotal)
            ->setMtoIGV(0)
            ->setTotalImpuestos(0)
            ->setValorVenta($montoTotal)
            ->setSubTotal($montoTotal)
            ->setMtoImpVenta($montoTotal);

        $details = [];
        foreach ($pedido->detalles as $detalle) {
            $detail = (new SaleDetail())
                ->setCodProducto('P' . str_pad($detalle->producto_id, 5, '0', STR_PAD_LEFT))
                ->setUnidad('NIU')
                ->setCantidad($detalle->cantidad)
                ->setMtoValorUnitario((float) $detalle->precio_unitario)
                ->setDescripcion($detalle->producto->nombre ?? 'Producto')
                ->setMtoBaseIgv((float) $detalle->subtotal)
                ->setPorcentajeIgv(0)
                ->setIgv(0)
                ->setTipAfeIgv('10')
                ->setTotalImpuestos(0)
                ->setMtoValorVenta((float) $detalle->subtotal)
                ->setMtoPrecioUnitario((float) $detalle->precio_unitario);

            $details[] = $detail;
        }

        $legend = (new Legend())
            ->setCode('1000')
            ->setValue($this->montoEnLetras($montoTotal));

        $invoice->setDetails($details)->setLegends([$legend]);

        return [
            'invoice' => $invoice,
            'base_imponible' => $montoTotal,
            'igv' => 0.00,
            'monto_total' => $montoTotal,
        ];
    }

    private function buildXmlFileName(Invoice $invoice): string
    {
        $tipoDocMap = ['01' => '01', '03' => '03'];
        $tipoDoc = $tipoDocMap[$invoice->getTipoDoc()] ?? '01';
        $ruc = config('sunat.ruc');
        $serie = $invoice->getSerie();
        $correlativo = $invoice->getCorrelativo();

        return "{$ruc}-{$tipoDoc}-{$serie}-{$correlativo}";
    }

    public function enviarComprobante(Pedido $pedido): array
    {
        $comprobante = $pedido->comprobante;
        if (!$comprobante) {
            return ['success' => false, 'message' => 'No existe comprobante para este pedido.'];
        }

        try {
            $data = $comprobante->tipo_comprobante === 'factura'
                ? $this->crearFactura($pedido)
                : $this->crearBoleta($pedido);

            $invoice = $data['invoice'];
            $xmlFileName = $this->buildXmlFileName($invoice);

            $xml = $this->see->getXmlSigned($invoice);

            $xmlDir = storage_path("app/sunat");
            @mkdir($xmlDir, 0755, true);

            $xmlPath = "{$xmlDir}/{$xmlFileName}.xml";
            file_put_contents($xmlPath, $xml);

            $result = $this->see->send($invoice);

            if (!$result->isSuccess()) {
                $comprobante->update(['estado_sincronizacion' => 'rechazado']);
                return [
                    'success' => false,
                    'message' => 'Error al conectar con SUNAT.',
                    'error_code' => $result->getError()->getCode(),
                    'error_message' => $result->getError()->getMessage(),
                ];
            }

            $cdr = $result->getCdrResponse();
            $code = (int) $cdr->getCode();

            if ($code === 0) {
                $comprobante->update(['estado_sincronizacion' => 'aceptado']);
                $estado = 'aceptado';
            } elseif ($code >= 2000 && $code <= 3999) {
                $comprobante->update(['estado_sincronizacion' => 'rechazado']);
                $estado = 'rechazado';
            } else {
                $comprobante->update(['estado_sincronizacion' => 'pendiente']);
                $estado = 'pendiente';
            }

            if ($result->getCdrZip()) {
                $cdrPath = "{$xmlDir}/R-{$xmlFileName}.zip";
                file_put_contents($cdrPath, $result->getCdrZip());
            }

            return [
                'success' => true,
                'estado' => $estado,
                'description' => $cdr->getDescription(),
                'notes' => $cdr->getNotes(),
                'xml_path' => $xmlPath,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Excepción: ' . $e->getMessage(),
            ];
        }
    }

    public function soloXml(Pedido $pedido): array
    {
        $comprobante = $pedido->comprobante;
        if (!$comprobante) {
            return ['success' => false, 'message' => 'No existe comprobante para este pedido.'];
        }

        try {
            $data = $comprobante->tipo_comprobante === 'factura'
                ? $this->crearFactura($pedido)
                : $this->crearBoleta($pedido);

            $invoice = $data['invoice'];
            $xml = $this->see->getXmlSigned($invoice);

            $xmlFileName = $this->buildXmlFileName($invoice);
            $xmlPath = storage_path("app/sunat/{$xmlFileName}.xml");
            @mkdir(dirname($xmlPath), 0755, true);
            file_put_contents($xmlPath, $xml);

            return [
                'success' => true,
                'xml_path' => $xmlPath,
                'xml_content' => $xml,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Excepción: ' . $e->getMessage(),
            ];
        }
    }
}
