<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MiembroSuscripcion;
use Carbon\Carbon;

class SuscripcionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'miembro_id' => 'required|exists:miembros,id',
            'plan_id' => 'required|exists:planes,id',
            'monto_pagado' => 'required|numeric|min:0',
            'fecha_inicio' => 'required|date',
        ]);

        $fechaInicio = Carbon::parse($request->fecha_inicio);
        $plan = \App\Models\Plan::findOrFail($request->plan_id);
        $fechaFin = $fechaInicio->copy()->addDays($plan->duracion_dias);

        MiembroSuscripcion::create([
            'miembro_id' => $request->miembro_id,
            'plan_id' => $request->plan_id,
            'promocion_id' => $request->promocion_id,
            'entrenador_id' => $request->entrenador_id,
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin,
            'monto_pagado' => $request->monto_pagado,
            'metodo_pago' => $request->metodo_pago,
            'referencia_pago' => $request->referencia_pago,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Suscripción registrada con éxito.'
        ]);
    }

    public function registrosRecientes()
    {
        $registros = MiembroSuscripcion::with(['miembro', 'plan'])
            ->whereDate('fecha_registro', Carbon::today())
            ->orderBy('fecha_registro', 'desc')
            ->get()
            ->map(function($suscripcion) {
                return [
                    'id' => $suscripcion->id,
                    'miembro_nombre' => $suscripcion->miembro->nombre,
                    'plan_nombre' => $suscripcion->plan->nombre_plan,
                    'monto_pagado' => $suscripcion->monto_pagado,
                    'metodo_pago' => $suscripcion->metodo_pago,
                    'fecha_registro' => Carbon::parse($suscripcion->fecha_registro)->format('Y-m-d H:i:s'),
                ];
            });

        return response()->json(['success' => true, 'data' => $registros]);
    }
}