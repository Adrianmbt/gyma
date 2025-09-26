<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte Diario de Operaciones</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 24px; }
        .header p { margin: 5px 0; font-size: 14px; }
        .section { margin-bottom: 25px; }
        .section h2 { font-size: 18px; border-bottom: 2px solid #333; padding-bottom: 5px; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .text-right { text-align: right; }
        .summary { margin-top: 20px; width: 50%; float: right; }
        .summary td, .summary th { border: none; }
        .summary th { text-align: right; padding-right: 10px; }
        .summary td { font-weight: bold; font-size: 14px; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 10px; color: #888; }
        .badge {
            display: inline-block;
            padding: .35em .65em;
            font-size: .75em;
            font-weight: 700;
            line-height: 1;
            color: #fff;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: .25rem;
        }
        .bg-success { background-color: #198754; }
        .bg-info { background-color: #0dcaf0; color: #000; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ config('app.name', 'Gimnasio') }}</h1>
        <p>Reporte Diario de Operaciones</p>
        <p><strong>Fecha:</strong> {{ $fecha }}</p>
    </div>

    @if($suscripciones->isEmpty() && $ventas->isEmpty())
        <div class="section">
            <p style="text-align: center; font-size: 16px;">No se registraron operaciones el día de hoy.</p>
        </div>
    @else
        @if($suscripciones->count() > 0)
        <div class="section">
            <h2><span class="badge bg-success">Suscripciones</span></h2>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Miembro</th>
                        <th>Plan</th>
                        <th>Método de Pago</th>
                        <th class="text-right">Monto</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($suscripciones as $suscripcion)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $suscripcion->miembro->nombre ?? 'N/A' }}</td>
                        <td>{{ $suscripcion->plan->nombre_plan ?? 'N/A' }}</td>
                        <td>{{ $suscripcion->metodo_pago }}</td>
                        <td class="text-right">${{ number_format($suscripcion->monto_pagado, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        @if($ventas->count() > 0)
        <div class="section">
            <h2><span class="badge bg-info">Ventas de Productos</span></h2>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Cliente</th>
                        <th>Productos</th>
                        <th class="text-right">Total Venta</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ventas as $venta)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $venta->miembro->nombre ?? 'Cliente General' }}</td>
                        <td>
                            <ul>
                                @foreach($venta->items as $item)
                                    <li>{{ $item->cantidad }}x {{ $item->producto->nombre_item ?? 'Producto no encontrado' }}</li>
                                @endforeach
                            </ul>
                        </td>
                        <td class="text-right">${{ number_format($venta->total_venta, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <div class="summary">
            <table>
                <tr>
                    <th>Total Suscripciones:</th>
                    <td>${{ number_format($ingresosSuscripciones, 2) }}</td>
                </tr>
                <tr>
                    <th>Total Ventas:</th>
                    <td>${{ number_format($ingresosVentas, 2) }}</td>
                </tr>
                <tr>
                    <th>Gran Total del Día:</th>
                    <td>${{ number_format($granTotal, 2) }}</td>
                </tr>
            </table>
        </div>
    @endif

    <div class="footer">
        Generado el {{ now()->format('d/m/Y H:i:s') }}
    </div>
</body>
</html>
