<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MiembroSuscripcion;
use App\Models\Venta;
use Carbon\Carbon;

class ReporteController extends Controller
{
    public function dashboardDia()
    {
        $hoy = Carbon::today();

        $totalSuscripciones = MiembroSuscripcion::whereDate('fecha_registro', $hoy)
            ->sum('monto_pagado');

        $totalVentas = Venta::whereDate('fecha_venta', $hoy)
            ->sum('total_venta');

        $granTotal = $totalSuscripciones + $totalVentas;

        return response()->json([
            'success' => true,
            'data' => [
                'total_suscripciones' => number_format($totalSuscripciones, 2),
                'total_ventas' => number_format($totalVentas, 2),
                'gran_total' => number_format($granTotal, 2),
            ]
        ]);
    }

    public function reporteDiario()
    {
        $hoy = Carbon::today();

        $suscripciones = MiembroSuscripcion::with(['miembro', 'plan'])
            ->whereDate('fecha_registro', $hoy)
            ->get();

        $ventas = Venta::with(['miembro', 'detalles.inventario'])
            ->whereDate('fecha_venta', $hoy)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'suscripciones' => $suscripciones,
                'ventas' => $ventas,
                'fecha' => $hoy->format('Y-m-d'),
            ]
        ]);
    }

    public function tendenciaSemanal()
    {
        $labels = [];
        $valores = [];

        for ($i = 6; $i >= 0; $i--) {
            $fecha = Carbon::today()->subDays($i);
            $labels[] = $fecha->locale('es')->isoFormat('ddd');

            $totalSuscripciones = MiembroSuscripcion::whereDate('fecha_registro', $fecha)
                ->sum('monto_pagado');

            $totalVentas = Venta::whereDate('fecha_venta', $fecha)
                ->sum('total_venta');

            $valores[] = floatval($totalSuscripciones + $totalVentas);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'labels' => $labels,
                'valores' => $valores
            ]
        ]);
    }

    public function transaccionesDia()
    {
        $hoy = Carbon::today();
        $transacciones = [];

        // Obtener suscripciones del día
        $suscripciones = MiembroSuscripcion::with(['miembro', 'plan'])
            ->whereDate('fecha_registro', $hoy)
            ->get();

        foreach ($suscripciones as $sub) {
            $transacciones[] = [
                'hora' => Carbon::parse($sub->fecha_registro)->format('H:i'),
                'tipo' => 'Suscripción',
                'miembro' => $sub->miembro->nombre ?? 'N/A',
                'concepto' => $sub->plan->nombre_plan ?? 'N/A',
                'monto' => $sub->monto_pagado,
                'metodo_pago' => $sub->metodo_pago ?? 'N/A'
            ];
        }

        // Obtener ventas del día
        $ventas = Venta::with(['miembro'])
            ->whereDate('fecha_venta', $hoy)
            ->get();

        foreach ($ventas as $venta) {
            $transacciones[] = [
                'hora' => Carbon::parse($venta->fecha_venta)->format('H:i'),
                'tipo' => 'Venta',
                'miembro' => $venta->miembro->nombre ?? 'N/A',
                'concepto' => 'Venta de productos',
                'monto' => $venta->total_venta,
                'metodo_pago' => $venta->metodo_pago ?? 'N/A'
            ];
        }

        // Ordenar por hora descendente
        usort($transacciones, function($a, $b) {
            return strcmp($b['hora'], $a['hora']);
        });

        return response()->json([
            'success' => true,
            'data' => $transacciones
        ]);
    }

    public function generarPdfDia()
    {
        $hoy = Carbon::today();

        $totalSuscripciones = MiembroSuscripcion::whereDate('fecha_registro', $hoy)
            ->sum('monto_pagado');

        $totalVentas = Venta::whereDate('fecha_venta', $hoy)
            ->sum('total_venta');

        $granTotal = $totalSuscripciones + $totalVentas;

        $suscripciones = MiembroSuscripcion::with(['miembro', 'plan'])
            ->whereDate('fecha_registro', $hoy)
            ->get();

        $ventas = Venta::with(['miembro', 'detalles.inventario'])
            ->whereDate('fecha_venta', $hoy)
            ->get();

        // Generar HTML para el PDF
        $html = view('reportes.pdf-dia', [
            'fecha' => $hoy->format('d/m/Y'),
            'totalSuscripciones' => $totalSuscripciones,
            'totalVentas' => $totalVentas,
            'granTotal' => $granTotal,
            'suscripciones' => $suscripciones,
            'ventas' => $ventas
        ])->render();

        // Configurar headers para PDF
        return response($html)
            ->header('Content-Type', 'text/html')
            ->header('Content-Disposition', 'inline; filename="reporte-' . $hoy->format('Y-m-d') . '.html"');
    }
}
