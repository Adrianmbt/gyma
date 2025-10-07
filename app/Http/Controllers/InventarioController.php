<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventario;

class InventarioController extends Controller
{
    public function listar()
    {
        $inventario = Inventario::with('area')->orderBy('nombre_item', 'asc')->get();
        return response()->json(['success' => true, 'data' => $inventario]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_item' => 'required|string|max:150',
            'tipo' => 'required|in:Tienda,Operaciones',
            'departamento' => 'required|string|max:100',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        $data = $request->only([
            'nombre_item', 'descripcion', 'tipo', 'departamento',
            'stock', 'precio', 'id_area', 'estado', 'fecha_adquisicion'
        ]);

        if ($request->item_id) {
            Inventario::findOrFail($request->item_id)->update($data);
            $message = 'Item actualizado con éxito.';
        } else {
            $data['codigo_item'] = 'IG-' . strtoupper(substr($request->departamento, 0, 3)) . '-' . time();
            $data['estado'] = $request->tipo === 'Tienda' ? 'Para la venta' : 'Operativo';
            Inventario::create($data);
            $message = 'Item registrado con éxito.';
        }

        return response()->json(['success' => true, 'message' => $message]);
    }

    public function show($id)
    {
        $item = Inventario::with('area')->findOrFail($id);
        return response()->json(['success' => true, 'data' => $item]);
    }

    public function destroy($id)
    {
        Inventario::findOrFail($id)->delete();
        return response()->json(['success' => true, 'message' => 'Item eliminado.']);
    }
}
