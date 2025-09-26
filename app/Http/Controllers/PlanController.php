<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PlanController extends Controller
{
    public function index()
    {
        return response()->json(['success' => true, 'data' => Plan::orderBy('nombre_plan')->get()]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre_plan' => 'required|string|max:255|unique:planes,nombre_plan',
            'descripcion' => 'nullable|string',
            'precio_base' => 'required|numeric|min:0',
            'duracion_dias' => 'required|integer|min:1',
            'estatus' => ['required', Rule::in(['activo', 'inactivo'])],
        ]);

        $plan = Plan::create($validated);
        return response()->json(['success' => true, 'message' => 'Plan creado con éxito.', 'data' => $plan], 201);
    }

    public function show(Plan $plan)
    {
        return response()->json(['success' => true, 'data' => $plan]);
    }

    public function update(Request $request, Plan $plan)
    {
        $validated = $request->validate([
            'nombre_plan' => ['required', 'string', 'max:255', Rule::unique('planes')->ignore($plan->id)],
            'descripcion' => 'nullable|string',
            'precio_base' => 'required|numeric|min:0',
            'duracion_dias' => 'required|integer|min:1',
            'estatus' => ['required', Rule::in(['activo', 'inactivo'])],
        ]);

        $plan->update($validated);
        return response()->json(['success' => true, 'message' => 'Plan actualizado con éxito.', 'data' => $plan]);
    }

    public function destroy(Plan $plan)
    {
        try {
            $plan->delete();
            return response()->json(['success' => true, 'message' => 'Plan eliminado con éxito.']);
        } catch (\Exception $e) {
            // Captura la excepción de violación de llave foránea
            if ($e instanceof \Illuminate\Database\QueryException && $e->errorInfo[1] == 1451) {
                return response()->json(['success' => false, 'message' => 'No se puede eliminar el plan porque tiene suscripciones asociadas. Considere cambiar su estado a "inactivo".'], 409);
            }
            return response()->json(['success' => false, 'message' => 'Error al eliminar el plan.'], 500);
        }
    }
}
