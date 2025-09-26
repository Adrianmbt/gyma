<?php

namespace App\Http\Controllers;

use App\Models\Registro;
use App\Models\Venta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RegistroController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        // Lógica para DataTables
        $draw = $request->input('draw');
        $start = $request->input('start');
        $length = $request->input('length');
        $searchValue = $request->input('search.value');

        // Consulta para suscripciones
        $suscripcionesQuery = DB::table('miembro_suscripciones')
            ->join('miembros', 'miembro_suscripciones.miembro_id', '=', 'miembros.id')
            ->join('planes', 'miembro_suscripciones.plan_id', '=', 'planes.id')
            ->select(
                'miembro_suscripciones.id',
                'miembro_suscripciones.fecha_inicio as fecha', // Usamos fecha_inicio como la fecha principal
                'miembros.nombre as miembro_nombre',
                DB::raw("'Suscripción' as tipo"),
                'planes.nombre_plan as detalles',
                'miembro_suscripciones.monto_pagado as monto',
                'miembro_suscripciones.referencia_pago as referencia'
            );

        // Consulta para ventas
        $ventasQuery = DB::table('ventas')
            ->join('miembros', 'ventas.miembro_id', '=', 'miembros.id')
            ->select(
                'ventas.id',
                'ventas.fecha_venta as fecha',
                'miembros.nombre as miembro_nombre',
                DB::raw("'Venta Tienda' as tipo"),
                DB::raw("'' as detalles"), // Los detalles se pueden agregar después si es necesario
                'ventas.total_venta as monto',
                DB::raw("'' as referencia")
            );

        // Aplicar búsqueda si existe
        if (!empty($searchValue)) {
            $suscripcionesQuery->where(function ($q) use ($searchValue) {
                $q->where('miembros.nombre', 'like', '%' . $searchValue . '%')
                  ->orWhere('planes.nombre_plan', 'like', '%' . $searchValue . '%');
            });
            $ventasQuery->where(function ($q) use ($searchValue) {
                $q->where('miembros.nombre', 'like', '%' . $searchValue . '%');
            });
        }

        // Unir las dos consultas
        $query = $suscripcionesQuery->union($ventasQuery);

        $totalRecords = DB::table(DB::raw("({$query->toSql()}) as sub"))->mergeBindings($query)->count();

        // Clonar la query para no afectar el conteo total
        $recordsQuery = DB::table(DB::raw("({$query->toSql()}) as sub"))
            ->mergeBindings($query)
            ->orderBy('fecha', 'desc')
            ->offset($start)
            ->limit($length);

        $records = $recordsQuery->get();

        // Formatear para DataTables
        $data = [];
        foreach ($records as $record) {
            $data[] = [
                'id' => $record->id,
                'fecha' => $record->fecha,
                'miembro_nombre' => $record->miembro_nombre,
                'tipo' => $record->tipo,
                'detalles' => $record->detalles,
                'monto' => $record->monto,
                'referencia' => $record->referencia,
            ];
        }

        return response()->json([
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords, // Simplificado, se puede mejorar
            'data' => $data,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
