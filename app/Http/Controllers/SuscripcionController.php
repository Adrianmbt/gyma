<?php

namespace App\Http\Controllers;

use App\Models\MiembroSuscripcion;
use App\Models\Miembro;
use App\Models\Plan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SuscripcionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Obtener historial de suscripciones de un miembro específico
        if ($request->has('miembro_id')) {
            $suscripciones = MiembroSuscripcion::where('miembro_id', $request->miembro_id)
                ->with('plan') // Carga la relación con el plan
                ->orderBy('fecha_inicio', 'desc')
                ->get();
            return response()->json(['success' => true, 'data' => $suscripciones]);
        }

        // Opcional: Listar todas las suscripciones (para un reporte general)
        $suscripciones = MiembroSuscripcion::with('miembro:id,nombre', 'plan:id,nombre_plan')
            ->orderBy('fecha_inicio', 'desc')
            ->get();
        return response()->json(['success' => true, 'data' => $suscripciones]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'miembro_id' => 'required|exists:miembros,id',
            'plan_id' => 'required|exists:planes,id',
            'monto_pagado' => 'required|numeric|min:0',
            'metodo_pago' => 'required|string',
            'referencia_pago' => 'nullable|string',
        ]);

        $plan = Plan::find($validated['plan_id']);
        $miembro = Miembro::find($validated['miembro_id']);

        // Determinar la fecha de inicio
        // Si el miembro tiene una suscripción activa, la nueva comienza cuando termina la anterior.
        // Si no, comienza hoy.
        $ultimaSuscripcion = $miembro->suscripciones()->orderBy('fecha_fin', 'desc')->first();
        $fechaInicio = Carbon::now();

        if ($ultimaSuscripcion && Carbon::parse($ultimaSuscripcion->fecha_fin)->isFuture()) {
            $fechaInicio = Carbon::parse($ultimaSuscripcion->fecha_fin)->addDay();
        }

        $fechaFin = $fechaInicio->copy()->addDays($plan->duracion_dias);

        $suscripcion = MiembroSuscripcion::create([
            'miembro_id' => $validated['miembro_id'],
            'plan_id' => $validated['plan_id'],
            'fecha_inicio' => $fechaInicio->toDateString(),
            'fecha_fin' => $fechaFin->toDateString(),
            'monto_pagado' => $validated['monto_pagado'],
            'metodo_pago' => $validated['metodo_pago'],
            'referencia_pago' => $validated['referencia_pago'],
        ]);

        // Actualizar el estado del miembro a 'activo'
        $miembro->estatus = 'activo';
        $miembro->save();

        return response()->json(['success' => true, 'message' => 'Suscripción registrada con éxito.', 'data' => $suscripcion], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MiembroSuscripcion  $suscripcion
     * @return \Illuminate\Http\Response
     */
    public function show(MiembroSuscripcion $suscripcion)
    {
        $suscripcion->load('miembro', 'plan');
        return response()->json(['success' => true, 'data' => $suscripcion]);
    }

    // NOTA: Generalmente, las suscripciones no se actualizan ni se eliminan directamente.
    // Se manejan creando nuevas suscripciones o ajustando estados.
    // Por lo tanto, los métodos update() y destroy() se dejan vacíos intencionadamente
    // a menos que se defina una lógica de negocio específica para ellos (ej. cancelar una suscripción).

    public function update(Request $request, MiembroSuscripcion $suscripcion)
    {
        return response()->json(['success' => false, 'message' => 'La actualización de suscripciones no está permitida.'], 405);
    }

    public function destroy(MiembroSuscripcion $suscripcion)
    {
        return response()->json(['success' => false, 'message' => 'La eliminación de suscripciones no está permitida.'], 405);
    }
}
