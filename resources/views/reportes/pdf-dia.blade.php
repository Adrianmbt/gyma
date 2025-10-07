<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Ingresos del Día - {{ $fecha }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #dc3545;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #dc3545;
            font-size: 28px;
            margin-bottom: 10px;
        }
        .header p {
            color: #666;
            font-size: 16px;
        }
        .summary {
            display: flex;
            justify-content: space-around;
            margin-bottom: 30px;
            gap: 20px;
        }
        .summary-card {
            flex: 1;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
        }
        .summary-card.success {
            background: #d1e7dd;
            border: 2px solid #198754;
        }
        .summary-card.info {
            background: #cff4fc;
            border: 2px solid #0dcaf0;
        }
        .summary-card.primary {
            background: #cfe2ff;
            border: 2px solid #0d6efd;
        }
        .summary-card h3 {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
        }
        .summary-card .amount {
            font-size: 32px;
            font-weight: bold;
            color: #333;
        }
        .section {
            margin-bottom: 30px;
        }
        .section h2 {
            color: #333;
            font-size: 20px;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #dee2e6;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table thead {
            background: #343a40;
            color: white;
        }
        table th, table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }
        table tbody tr:hover {
            background: #f8f9fa;
        }
        .text-end {
            text-align: right;
        }
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }
        .badge-success {
            background: #198754;
            color: white;
        }
        .badge-info {
            background: #0dcaf0;
            color: white;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #dee2e6;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
        .no-data {
            text-align: center;
            padding: 20px;
            color: #999;
            font-style: italic;
        }
        @media print {
            body {
                background: white;
            }
            .container {
                box-shadow: none;
            }
            button {
                display: none;
            }
        }
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 12px 24px;
            background: #dc3545;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        .print-button:hover {
            background: #bb2d3b;
        }
    </style>
</head>
<body>
    <button class="print-button" onclick="window.print()">🖨️ Imprimir / Guardar PDF</button>
    
    <div class="container">
        <div class="header">
            <h1>INFINITY GYM CENTER</h1>
            <p>Reporte de Ingresos del Día</p>
            <p><strong>Fecha:</strong> {{ $fecha }}</p>
        </div>

        <div class="summary">
            <div class="summary-card success">
                <h3>Ingresos por Suscripciones</h3>
                <div class="amount">${{ number_format($totalSuscripciones, 2) }}</div>
            </div>
            <div class="summary-card info">
                <h3>Ingresos por Ventas</h3>
                <div class="amount">${{ number_format($totalVentas, 2) }}</div>
            </div>
            <div class="summary-card primary">
                <h3>Gran Total del Día</h3>
                <div class="amount">${{ number_format($granTotal, 2) }}</div>
            </div>
        </div>

        <div class="section">
            <h2>📋 Detalle de Suscripciones</h2>
            @if($suscripciones->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>Hora</th>
                            <th>Miembro</th>
                            <th>Plan</th>
                            <th>Método de Pago</th>
                            <th class="text-end">Monto</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($suscripciones as $sub)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($sub->fecha_registro)->format('H:i') }}</td>
                            <td>{{ $sub->miembro->nombre ?? 'N/A' }}</td>
                            <td>{{ $sub->plan->nombre_plan ?? 'N/A' }}</td>
                            <td>{{ $sub->metodo_pago ?? 'N/A' }}</td>
                            <td class="text-end"><strong>${{ number_format($sub->monto_pagado, 2) }}</strong></td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr style="background: #f8f9fa; font-weight: bold;">
                            <td colspan="4" class="text-end">SUBTOTAL:</td>
                            <td class="text-end">${{ number_format($totalSuscripciones, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            @else
                <div class="no-data">No hay suscripciones registradas hoy</div>
            @endif
        </div>

        <div class="section">
            <h2>🛒 Detalle de Ventas</h2>
            @if($ventas->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>Hora</th>
                            <th>Miembro</th>
                            <th>Productos</th>
                            <th>Método de Pago</th>
                            <th class="text-end">Monto</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ventas as $venta)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($venta->fecha_venta)->format('H:i') }}</td>
                            <td>{{ $venta->miembro->nombre ?? 'N/A' }}</td>
                            <td>
                                @foreach($venta->detalles as $detalle)
                                    {{ $detalle->inventario->nombre_item ?? 'N/A' }} ({{ $detalle->cantidad }})
                                    @if(!$loop->last), @endif
                                @endforeach
                            </td>
                            <td>{{ $venta->metodo_pago ?? 'N/A' }}</td>
                            <td class="text-end"><strong>${{ number_format($venta->total_venta, 2) }}</strong></td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr style="background: #f8f9fa; font-weight: bold;">
                            <td colspan="4" class="text-end">SUBTOTAL:</td>
                            <td class="text-end">${{ number_format($totalVentas, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            @else
                <div class="no-data">No hay ventas registradas hoy</div>
            @endif
        </div>

        <div class="section">
            <table>
                <tfoot>
                    <tr style="background: #0d6efd; color: white; font-size: 18px; font-weight: bold;">
                        <td colspan="4" class="text-end">GRAN TOTAL DEL DÍA:</td>
                        <td class="text-end">${{ number_format($granTotal, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="footer">
            <p>Reporte generado el {{ now()->format('d/m/Y H:i:s') }}</p>
            <p>Infinity Gym Center - Sistema de Gestión</p>
        </div>
    </div>

    <script>
        // Auto-abrir diálogo de impresión después de cargar
        window.onload = function() {
            // Esperar un momento para que se renderice todo
            setTimeout(function() {
                // window.print(); // Descomentado para auto-imprimir
            }, 500);
        };
    </script>
</body>
</html>
