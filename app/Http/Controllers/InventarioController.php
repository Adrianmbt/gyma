<?php

namespace App\Http\Controllers;

use App\Models\Inventario;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class InventarioController extends Controller
{
    public function index(Request $request)
    {
        $query = Inventario::query();

        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('nombre_item', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('codigo_item', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('departamento', 'LIKE', "%{$searchTerm}%");
            });
        }

        $inventario = $query->orderBy('nombre_item')->get();
        return response()->json(['success' => true, 'data' => $inventario]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre_item' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'departamento' => 'required|string|max:100',
            'estado' => ['required', Rule::in(['Operativo', 'Mantenimiento', 'De Baja'])],
            'stock' => 'required|integer|min:0',
            'precio' => 'required|numeric|min:0',
            'fecha_adquisicion' => 'nullable|date',
        ]);

        // Lógica para asignar id_area y tipo basado en el departamento
        $mapa_area = [
            'Suplementos' => 1, 'Bebidas' => 1, 'Accesorios de Venta' => 1, 'Lenceria' => 1, 'Lencería' => 1,
            'Maquinas' => 2, 'Discos y Pesas' => 2, 'Mancuernas' => 2, 'Accesorios de Gym' => 2
        ];
        $id_area = $mapa_area[$validated['departamento']] ?? null;

        if ($id_area === null) {
            return response()->json(['success' => false, 'message' => "El departamento '{$validated['departamento']}' no es válido."], 422);
        }

        $validated['id_area'] = $id_area;
        $validated['tipo'] = ($id_area === 1) ? 'Tienda' : 'Operaciones';
        $validated['codigo_item'] = 'IG-' . strtoupper(substr($validated['departamento'], 0, 3)) . '-' . time();

        $item = Inventario::create($validated);
        return response()->json(['success' => true, 'message' => 'Item agregado con éxito.', 'data' => $item], 201);
    }

    public function show(Inventario $inventario)
    {
        return response()->json(['success' => true, 'data' => $inventario]);
    }

    public function update(Request $request, Inventario $inventario)
    {
        $validated = $request->validate([
            'nombre_item' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'departamento' => 'required|string|max:100',
            'estado' => ['required', Rule::in(['Operativo', 'Mantenimiento', 'De Baja'])],
            'stock' => 'required|integer|min:0',
            'precio' => 'required|numeric|min:0',
            'fecha_adquisicion' => 'nullable|date',
        ]);

        $mapa_area = [
            'Suplementos' => 1, 'Bebidas' => 1, 'Accesorios de Venta' => 1, 'Lenceria' => 1, 'Lencería' => 1,
            'Maquinas' => 2, 'Discos y Pesas' => 2, 'Mancuernas' => 2, 'Accesorios de Gym' => 2
        ];
        $id_area = $mapa_area[$validated['departamento']] ?? null;

        if ($id_area === null) {
            return response()->json(['success' => false, 'message' => "El departamento '{$validated['departamento']}' no es válido."], 422);
        }

        $validated['id_area'] = $id_area;
        $validated['tipo'] = ($id_area === 1) ? 'Tienda' : 'Operaciones';

        $inventario->update($validated);
        return response()->json(['success' => true, 'message' => 'Item actualizado con éxito.', 'data' => $inventario]);
    }

    public function destroy(Inventario $inventario)
    {
        try {
            $inventario->delete();
            return response()->json(['success' => true, 'message' => 'Item eliminado con éxito.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'No se pudo eliminar el item.'], 500);
        }
    }
}
