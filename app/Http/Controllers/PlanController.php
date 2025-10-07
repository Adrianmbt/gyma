<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plan;

class PlanController extends Controller
{
    public function listar()
    {
        $planes = Plan::orderBy('nombre_plan', 'asc')->get();
        return response()->json(['success' => true, 'data' => $planes]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_plan' => 'required|string|max:150',
            'precio_base_plan' => 'required|numeric|min:0',
            'duracion_dias_plan' => 'required|integer|min:1',
        ]);

        $data = [
            'nombre_plan' => $request->nombre_plan,
            'descripcion' => $request->descripcion_plan,
            'precio_base' => $request->precio_base_plan,
            'duracion_dias' => $request->duracion_dias_plan,
            'estatus' => $request->estatus_plan ?? 'activo',
        ];

        if ($request->plan_id) {
            Plan::findOrFail($request->plan_id)->update($data);
            $message = 'Plan actualizado con éxito.';
        } else {
            Plan::create($data);
            $message = 'Plan creado con éxito.';
        }

        return response()->json(['success' => true, 'message' => $message]);
    }

    public function show($id)
    {
        $plan = Plan::findOrFail($id);
        return response()->json(['success' => true, 'data' => $plan]);
    }

    public function destroy($id)
    {
        try {
            Plan::findOrFail($id)->delete();
            return response()->json(['success' => true, 'message' => 'Plan eliminado.']);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar el plan porque ya hay miembros suscritos a él.'
            ], 400);
        }
    }
}
