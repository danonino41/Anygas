<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ticket {{ $serie }}-{{ $correlativo }}</title>
    <style>
        @page { size: 80mm auto; margin: 2mm; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Consolas', 'Courier New', monospace;
            font-size: 11px;
            width: 76mm;
            color: #000;
            background: #fff;
            padding: 2mm;
        }
        .center { text-align: center; }
        .bold { font-weight: 700; }
        .line { border-top: 1px dashed #000; margin: 4px 0; }
        .line-double { border-top: 2px solid #000; margin: 4px 0; }
        .separator { text-align: center; letter-spacing: 2px; font-size: 9px; margin: 3px 0; }

        .header { margin-bottom: 4px; }
        .header .empresa { font-size: 13px; font-weight: 700; }
        .header .ruc { font-size: 10px; }
        .header .direccion { font-size: 9px; line-height: 1.3; }

        .comprobante-info { margin: 4px 0; }
        .comprobante-info .tipo { font-size: 12px; font-weight: 700; }

        .cliente-info { margin: 4px 0; }
        .cliente-info .row-line { display: flex; justify-content: space-between; font-size: 10px; line-height: 1.5; }
        .cliente-info .label { font-weight: 600; }

        .items-table { width: 100%; margin: 4px 0; font-size: 10px; }
        .items-table th { text-align: left; font-weight: 600; border-bottom: 1px solid #000; padding: 1px 0; }
        .items-table td { padding: 1px 0; vertical-align: top; }
        .items-table .col-cant { width: 12%; text-align: center; }
        .items-table .col-desc { width: 52%; }
        .items-table .col-pu { width: 18%; text-align: right; }
        .items-table .col-st { width: 18%; text-align: right; }
        .items-table .desc-line { font-size: 9px; color: #444; }

        .totals { margin: 4px 0; }
        .totals .row-line { display: flex; justify-content: space-between; font-size: 10px; line-height: 1.6; }
        .totals .row-total { font-size: 13px; font-weight: 700; border-top: 2px solid #000; padding-top: 3px; margin-top: 2px; }

        .pago-info { margin: 4px 0; font-size: 10px; line-height: 1.5; }

        .footer { margin-top: 6px; text-align: center; font-size: 9px; line-height: 1.4; }
        .footer .gracias { font-size: 12px; font-weight: 700; margin: 4px 0; }
        .footer .codigo { font-size: 8px; letter-spacing: 1px; margin-top: 4px; word-break: break-all; }

    </style>
</head>
<body>

    <div class="header center">
        <div class="empresa">{{ $company['nombre_comercial'] }}</div>
        <div class="ruc">RUC: {{ $company['ruc'] }}</div>
        <div class="direccion">{{ $company['razon_social'] }}</div>
        <div class="direccion">{{ $company['direccion'] }}</div>
        <div class="direccion">{{ $company['distrito'] }}, {{ $company['provincia'] }}, {{ $company['departamento'] }}</div>
    </div>

    <div class="line-double"></div>

    <div class="comprobante-info center">
        <div class="tipo">{{ strtoupper($tipoLabel) }}</div>
        <div style="font-size:10px;">{{ $serie }}-{{ $correlativo }}</div>
        <div style="font-size:9px;">Fecha: {{ $fechaEmision }}</div>
        <div style="font-size:9px;">Hora: {{ $horaEmision }}</div>
    </div>

    <div class="line"></div>

    <div class="cliente-info">
        <div class="row-line">
            <span class="label">CLIENTE:</span>
            <span>{{ $cliente['nombre'] }}</span>
        </div>
        <div class="row-line">
            <span class="label">{{ $cliente['doc_tipo'] }}:</span>
            <span>{{ $cliente['documento'] }}</span>
        </div>
        @if(!empty($cliente['telefono']))
        <div class="row-line">
            <span class="label">TEL:</span>
            <span>{{ $cliente['telefono'] }}</span>
        </div>
        @endif
        @if(!empty($cliente['direccion']))
        <div class="row-line">
            <span class="label">DIR:</span>
            <span>{{ $cliente['direccion'] }}</span>
        </div>
        @endif
    </div>

    <div class="line"></div>

    <table class="items-table">
        <thead>
            <tr>
                <th class="col-cant">Cant</th>
                <th class="col-desc">Descripcion</th>
                <th class="col-pu">P.Unit</th>
                <th class="col-st">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($detalles as $item)
            <tr>
                <td class="col-cant">{{ $item['cantidad'] }}</td>
                <td class="col-desc">
                    {{ $item['descripcion'] }}
                    @if(!empty($item['marca']))
                    <div class="desc-line">{{ $item['marca'] }}</div>
                    @endif
                </td>
                <td class="col-pu">S/ {{ number_format($item['precio_unitario'], 2) }}</td>
                <td class="col-st">S/ {{ number_format($item['subtotal'], 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="line"></div>

    <div class="totals">
        @if($tipoComprobante === 'factura')
        <div class="row-line">
            <span>Op. Gravada:</span>
            <span>S/ {{ number_format($totales['base_imponible'], 2) }}</span>
        </div>
        <div class="row-line">
            <span>IGV (18%):</span>
            <span>S/ {{ number_format($totales['igv'], 2) }}</span>
        </div>
        @endif
        <div class="row-line row-total">
            <span>TOTAL:</span>
            <span>S/ {{ number_format($totales['monto_total'], 2) }}</span>
        </div>
    </div>

    <div class="line"></div>

    <div class="pago-info">
        @if(!empty($pagos))
            @foreach($pagos as $pago)
            <div class="row-line" style="display:flex; justify-content:space-between;">
                <span>Pago: {{ $pago['tipo'] }}</span>
                <span>S/ {{ number_format($pago['monto'], 2) }}</span>
            </div>
            @endforeach
        @else
            <div style="display:flex; justify-content:space-between;">
                <span>Pago:</span>
                <span>S/ {{ number_format($totales['monto_total'], 2) }}</span>
            </div>
        @endif
    </div>

    <div class="line-double"></div>

    <div class="footer">
        <div class="gracias">GRACIAS POR SU COMPRA</div>
        <div>Conserve este ticket para</div>
        <div>cualquier reclamo o devolucion</div>
        <div style="margin-top:4px;">
            <div class="codigo">COD: {{ $codigoSeguimiento }}</div>
        </div>
        @if(!empty($vendedor))
        <div style="margin-top:3px;">Atendido por: {{ $vendedor }}</div>
        @endif
    </div>

    <div class="line"></div>

</body>
</html>
