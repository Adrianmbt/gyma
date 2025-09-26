<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Registro;
use App\Models\Venta;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ReporteController extends Controller
{
    /**
     * Genera un reporte en PDF de las operaciones del día.
     */
    public function reporteDiarioPDF()
    {
        $hoy = Carbon::today();

        // Obtener ingresos por suscripciones del día
        $ingresosSuscripciones = Registro::whereDate('fecha_pago', $hoy)
            ->where('tipo_registro', 'pago')
            ->sum('monto_pagado');

        // Obtener ingresos por ventas del día
        $ingresosVentas = Venta::whereDate('fecha_venta', $hoy)
            ->sum('total_venta');
        
        // Obtener detalles de las transacciones
        $suscripciones = Registro::with('miembro', 'plan')
            ->whereDate('fecha_pago', $hoy)
            ->where('tipo_registro', 'pago')
            ->get();

        $ventas = Venta::with('miembro', 'items.producto')
            ->whereDate('fecha_venta', $hoy)
            ->get();

        $granTotal = $ingresosSuscripciones + $ingresosVentas;

        // Preparar los datos para la vista
        $data = [
            'fecha' => $hoy->format('d/m/Y'),
            'ingresosSuscripciones' => $ingresosSuscripciones,
            'ingresosVentas' => $ingresosVentas,
            'granTotal' => $granTotal,
            'suscripciones' => $suscripciones,
            'ventas' => $ventas,
        ];

        // Cargar la vista y generar el PDF
        $pdf = PDF::loadView('reportes.diario_pdf', $data);

        // Descargar el PDF o mostrarlo en el navegador
        return $pdf->stream('reporte-diario-' . $hoy->format('Y-m-d') . '.pdf');
    }

    /**
     * Obtiene las estadísticas del día para el dashboard.
     */
    public function getDashboardStats()
    {
        $hoy = Carbon::today();

        $totalSuscripciones = Registro::whereDate('fecha_pago', $hoy)
            ->where('tipo_registro', 'pago')
            ->sum('monto_pagado');

        $totalVentas = Venta::whereDate('fecha_venta', $hoy)
            ->sum('total_venta');

        return response()->json([
            'total_suscripciones_dia' => $totalSuscripciones,
            'total_ventas_dia' => $totalVentas,
            'gran_total_dia' => $totalSuscripciones + $totalVentas,
        ]);
    }
}

