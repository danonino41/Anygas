<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Comprobante Electrónico</title>
    <style>
        body { 
            font-family: Arial, Helvetica, sans-serif; 
            font-size: 11px; 
            color: #000; 
            line-height: 1.3;
            margin: 0;
            padding: 0;
        }
        
        .ticket-container {
            width: 100%;
            max-width: 720px;
            margin: 0 auto;
        }
        
        table { width: 100%; border-collapse: collapse; }
        td { vertical-align: top; }
        
        .emisor-title { font-size: 11px; font-weight: bold; text-transform: uppercase; margin-bottom: 2px; }
        .emisor-subtitle { font-size: 11px; font-weight: bold; text-transform: uppercase; margin-bottom: 5px; }
        .emisor-data { font-size: 9.5px; text-transform: uppercase; margin-bottom: 2px; }
        
        .invoice-box { 
            border: 1px solid #000; 
            padding: 10px 0;
            text-align: center;
            width: 98%;
            float: right;
        }
        .invoice-box h2 { margin: 2px 0; font-size: 11px; font-weight: bold; letter-spacing: 0.5px; }
        .invoice-box h3 { margin: 5px 0 2px 0; font-size: 12px; font-weight: bold; }
        
        .client-table { margin-top: 15px; margin-bottom: 12px; }
        .client-table td { padding: 2px 0; font-size: 11px; }
        .client-table td.label { width: 130px; }
        .client-table td.separator { width: 15px; text-align: left; }
        
        .items-table { margin-bottom: 15px; border: 1px solid #000; }
        .items-table th { 
            border: 1px solid #000; 
            padding: 4px; 
            font-size: 11px; 
            font-weight: bold;
            text-align: center;
        }
        .items-table td { 
            padding: 5px 4px; 
            font-size: 11px;
            vertical-align: middle;
        }
        .items-table tbody td { border-left: 1px solid #000; border-right: 1px solid #000; }
        .items-table tbody tr:last-child td { border-bottom: 1px solid #000; }

        .text-left { text-align: left; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }

        .free-ops-box { 
            padding: 4px 6px; 
            font-size: 11px;
        }
        
        .totals-cell { 
            border: 1px solid #000; 
            padding: 3px 5px; 
            font-size: 11px; 
            text-align: right;
        }
        
        .sunat-notice-box { 
            border: 1px solid #000; 
            padding: 6px 10px; 
            text-align: center; 
            font-size: 11px; 
            margin-top: 12px;
        }
    </style>
</head>
<body>

<div class="ticket-container" style="border: 1px solid #000; padding: 10px;">

    @php
        $esFactura = $tipoComprobante === 'factura';
        $nombreComprobante = $esFactura ? 'FACTURA ELECTRÓNICA' : 'BOLETA DE VENTA ELECTRÓNICA';
    @endphp

    <table>
        <tr>
            <td width="60%" style="padding-top: 5px;">
                <div class="emisor-title">{{ $company['nombre_comercial'] }}</div>
                <div class="emisor-subtitle">{{ $company['razon_social'] }}</div>
                <div class="emisor-data">{{ $company['direccion'] }}</div>
                <div class="emisor-data">
                    {{ $company['distrito'] }} - 
                    {{ $company['provincia'] }} - 
                    {{ $company['departamento'] }}
                </div>
            </td>
            <td width="40%">
                <div class="invoice-box">
                    <h2>{{ $nombreComprobante }}</h2>
                    <h2>RUC: {{ $company['ruc'] }}</h2>
                    <h3>{{ $serie }}-{{ $correlativo }}</h3>
                </div>
            </td>
        </tr>
    </table>

    <hr style="margin-top:15px; background-color: #000; height: 1px; border: none;">

    <table class="client-table">
        <tr>
            <td class="label">Fecha de Vencimiento</td>
            <td class="separator">:</td>
            <td style="font-weight: bold;">-</td>
        </tr>
        <tr>
            <td class="label">Fecha de Emisión</td>
            <td class="separator">:</td>
            <td style="font-weight: bold;">{{ $fechaEmision }}</td>
        </tr>
        <tr>
            <td class="label">Señor(es)</td>
            <td class="separator">:</td>
            <td style="font-weight: bold; text-transform: uppercase;">{{ $cliente['nombre'] }}</td>
        </tr>
        <tr>
            <td class="label">{{ $esFactura ? 'RUC' : 'DNI' }}</td>
            <td class="separator">:</td>
            <td style="font-weight: bold;">{{ $cliente['documento'] }}</td>
        </tr>
        <tr>
            <td class="label">Dirección del Cliente</td>
            <td class="separator">:</td>
            <td style="font-weight: bold; text-transform: uppercase;">{{ $cliente['direccion'] ?? 'SIN DIRECCION' }}</td>
        </tr>
        <tr>
            <td class="label">Tipo de Moneda</td>
            <td class="separator">:</td>
            <td style="font-weight: bold; text-transform: uppercase;">{{ $moneda }}</td>
        </tr>
        <tr>
            <td class="label">Observación</td>
            <td class="separator">:</td>
            <td style="font-weight: bold;"></td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th width="9%">Cantidad</th>
                <th width="13%">Unidad Medida</th>
                <th width="12%">Código</th>
                <th width="46%">Descripción</th>
                @if($esFactura)
                    <th width="20%">Valor Unitario</th>
                    <th width="20%">Precio <span>(Con IGV)</span></th>
                @else
                    <th width="20%">Precio Unitario</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($detalles as $detalle)
            <tr>
                <td class="text-right">{{ number_format($detalle['cantidad'], 2) }}</td>
                <td class="text-center">{{ $detalle['unidad'] == 'NIU' || $detalle['unidad'] == 'ZZ' ? 'UNIDAD' : $detalle['unidad'] }}</td>
                <td class="text-left">{{ $detalle['codigo'] }}</td>
                <td class="text-left">{{ $detalle['codigo'] }} {{ $detalle['descripcion'] }}</td>
                @if($esFactura)
                    <td class="text-right">{{ number_format($detalle['valor_unitario'], 10) }}</td>
                    <td class="text-right">{{ number_format($detalle['precio_unitario'], 2) }}</td>
                @else
                    <td class="text-right">{{ number_format($detalle['precio_unitario'], 2) }}</td>
                @endif
            </tr>
            @endforeach
        </tbody>
    </table>

    <table width="100%">
        <tr>
            <td width="62%" style="padding-right: 10px;">
                <table width="100%">
                    <tr>
                        <td>
                            <div class="free-ops-box">
                                Valor de Venta de Operaciones Gratuitas : <span style="border: 1px solid #000; padding: 2px; width: 150px;">S/. 0.00</span>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-top: 15px; font-weight: bold; text-transform: uppercase; font-size: 11px;">
                            {{ $leyenda }}
                        </td>
                    </tr>
                </table>
            </td>
            
            <td width="38%">
                <table width="100%">
                    @if($esFactura)
                        <tr>
                            <td class="totals-cell text-right" width="55%">Sub Total Ventas :</td>
                            <td class="totals-cell text-right" width="45%">S/. {{ number_format($totales['base_imponible'], 2) }}</td>
                        </tr>
                        <tr>
                            <td class="totals-cell text-right">Anticipos :</td>
                            <td class="totals-cell text-right">S/. 0.00</td>
                        </tr>
                        <tr>
                            <td class="totals-cell text-right">Descuentos :</td>
                            <td class="totals-cell text-right">S/. 0.00</td>
                        </tr>
                        <tr>
                            <td class="totals-cell text-right">Valor Venta :</td>
                            <td class="totals-cell text-right">S/. {{ number_format($totales['base_imponible'], 2) }}</td>
                        </tr>
                        <tr>
                            <td class="totals-cell text-right">ISC :</td>
                            <td class="totals-cell text-right">S/. 0.00</td>
                        </tr>
                        <tr>
                            <td class="totals-cell text-right">IGV :</td>
                            <td class="totals-cell text-right">S/. {{ number_format($totales['igv'], 2) }}</td>
                        </tr>
                        <tr>
                            <td class="totals-cell text-right">Otros Cargos :</td>
                            <td class="totals-cell text-right">S/. 0.00</td>
                        </tr>
                        <tr>
                            <td class="totals-cell text-right">Otros Tributos :</td>
                            <td class="totals-cell text-right">S/. 0.00</td>
                        </tr>
                    @endif
                    <tr>
                        <td class="totals-cell text-right" style="font-weight: bold;">Importe Total :</td>
                        <td class="totals-cell text-right" style="font-weight: bold;">S/. {{ number_format($totales['monto_total'], 2) }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <div class="sunat-notice-box">
        <p style="font-weight: bold; text-transform: uppercase;">ESTE COMPROBANTE ELECTRÓNICO FUE AUTORIZADO POR SUNAT</p>
        <p>Esta es una representación impresa de la {{ $nombreComprobante }}. Puede verificarla utilizando su clave SOL.</p>
    </div>
</div>
</body>
</html>
