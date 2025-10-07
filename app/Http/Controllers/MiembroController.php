<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Miembro;
use App\Models\MiembroSuscripcion;
use Illuminate\Support\Facades\Storage;

class MiembroController extends Controller
{
    public function listar(Request $request)
    {
        $draw = $request->input('draw', 1);
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $searchValue = $request->input('search.value', '');

        $query = Miembro::with('suscripcionActiva.plan');

        if (!empty($searchValue)) {
            $query->where(function($q) use ($searchValue) {
                $q->where('nombre', 'like', "%{$searchValue}%")
                  ->orWhere('numero_cedula', 'like', "%{$searchValue}%")
                  ->orWhere('telefono', 'like', "%{$searchValue}%");
            });
        }

        $totalRecords = Miembro::count();
        $totalFilteredRecords = $query->count();

        $miembros = $query->orderBy('nombre', 'asc')
            ->skip($start)
            ->take($length)
            ->get()
            ->map(function($miembro) {
                return [
                    'id' => $miembro->id,
                    'nombre' => $miembro->nombre,
                    'numero_cedula' => $miembro->numero_cedula,
                    'telefono' => $miembro->telefono,
                    'ruta_foto' => $miembro->ruta_foto,
                    'estatus' => $miembro->estatus,
                    'estatus_suscripcion' => $miembro->estatus_suscripcion,
                ];
            });

        return response()->json([
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalFilteredRecords,
            'data' => $miembros,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'telefono' => 'required|string|max:30',
            'numero_cedula' => 'required|string|max:20|unique:miembros,numero_cedula,' . $request->miembro_id,
            'fecha_nacimiento' => 'required|date',
        ]);

        $data = $request->only(['nombre', 'telefono', 'numero_cedula', 'fecha_nacimiento']);
        
        // Siempre usar imagen por defecto
        $data['ruta_foto'] = 'uploads/default.png';

        if ($request->miembro_id) {
            $miembro = Miembro::findOrFail($request->miembro_id);
            $miembro->update($data);
            $message = 'Miembro actualizado con éxito.';
        } else {
            $data['estatus'] = 'activo';
            Miembro::create($data);
            $message = 'Miembro registrado con éxito.';
        }

        return response()->json(['success' => true, 'message' => $message]);
    }

    public function show($id)
    {
        $miembro = Miembro::findOrFail($id);
        return response()->json(['success' => true, 'data' => $miembro]);
    }

    public function getByCedula(Request $request)
    {
        $miembro = Miembro::with(['suscripcionActiva.plan', 'suscripciones' => function($query) {
            $query->orderBy('fecha_registro', 'desc')->limit(5);
        }])
            ->where('numero_cedula', $request->cedula)
            ->first();

        if (!$miembro) {
            return response()->json([
                'success' => false,
                'message' => 'Miembro no encontrado.'
            ], 404);
        }

        $suscripcionActiva = $miembro->suscripcionActiva;
        $diasRestantes = null;
        $vencida = false;
        
        if ($suscripcionActiva) {
            $fechaFin = \Carbon\Carbon::parse($suscripcionActiva->fecha_fin);
            $hoy = \Carbon\Carbon::now();
            $diasRestantes = $hoy->diffInDays($fechaFin, false);
            $vencida = $diasRestantes < 0;
        }

        $data = [
            'id' => $miembro->id,
            'nombre' => $miembro->nombre,
            'numero_cedula' => $miembro->numero_cedula,
            'telefono' => $miembro->telefono,
            'fecha_nacimiento' => $miembro->fecha_nacimiento ? $miembro->fecha_nacimiento->format('Y-m-d') : null,
            'ruta_foto' => $miembro->ruta_foto,
            'edad' => $miembro->edad,
            'estatus' => $miembro->estatus,
            'estatus_suscripcion' => $miembro->estatus_suscripcion,
            'fecha_registro' => $miembro->fecha_registro ? \Carbon\Carbon::parse($miembro->fecha_registro)->format('Y-m-d') : date('Y-m-d'),
            
            // Suscripción actual
            'tiene_suscripcion' => $suscripcionActiva ? true : false,
            'nombre_membresia' => $suscripcionActiva && $suscripcionActiva->plan ? $suscripcionActiva->plan->nombre_plan : 'Sin Suscripción',
            'plan_id' => $suscripcionActiva ? $suscripcionActiva->plan_id : null,
            'fecha_inicio_suscripcion' => $suscripcionActiva && $suscripcionActiva->fecha_inicio ? $suscripcionActiva->fecha_inicio->format('Y-m-d') : null,
            'fecha_fin_suscripcion' => $suscripcionActiva && $suscripcionActiva->fecha_fin ? $suscripcionActiva->fecha_fin->format('Y-m-d') : null,
            'dias_restantes' => $diasRestantes,
            'suscripcion_vencida' => $vencida,
            'monto_pagado' => $suscripcionActiva ? $suscripcionActiva->monto_pagado : 0,
            'metodo_pago' => $suscripcionActiva ? $suscripcionActiva->metodo_pago : null,
            
            // Historial de suscripciones
            'historial_suscripciones' => $miembro->suscripciones->map(function($sub) {
                return [
                    'id' => $sub->id,
                    'plan' => $sub->plan ? $sub->plan->nombre_plan : 'N/A',
                    'fecha_inicio' => $sub->fecha_inicio ? $sub->fecha_inicio->format('Y-m-d') : 'N/A',
                    'fecha_fin' => $sub->fecha_fin ? $sub->fecha_fin->format('Y-m-d') : 'N/A',
                    'monto' => $sub->monto_pagado,
                    'fecha_registro' => $sub->fecha_registro ? \Carbon\Carbon::parse($sub->fecha_registro)->format('Y-m-d H:i') : 'N/A',
                ];
            }),
        ];

        return response()->json(['success' => true, 'data' => $data]);
    }

    public function updateStatus(Request $request)
    {
        $miembro = Miembro::findOrFail($request->id);
        $miembro->update(['estatus' => $request->estatus]);

        return response()->json([
            'success' => true,
            'message' => 'Estatus actualizado correctamente.'
        ]);
    }

    public function vetar($id)
    {
        try {
            $miembro = Miembro::findOrFail($id);
            $miembro->estatus = 'vetado';
            $miembro->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Miembro suspendido correctamente. No podrá acceder al gimnasio.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al suspender miembro: ' . $e->getMessage()
            ], 500);
        }
    }

    public function reactivar($id)
    {
        try {
            $miembro = Miembro::findOrFail($id);
            $miembro->estatus = 'activo';
            $miembro->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Miembro reactivado correctamente. Ya puede acceder al gimnasio.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al reactivar miembro: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        Miembro::findOrFail($id)->delete();
        return response()->json([
            'success' => true,
            'message' => 'Miembro eliminado permanentemente.'
        ]);
    }
}
