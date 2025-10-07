<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venta;
use App\Models\VentaDetalle;
use App\Models\Inventario;
use Illuminate\Support\Facades\DB;

class VentaController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'miembro_id' => 'required|exists:miembros,id',
            'items' => 'required|array|min:1',
            'items.*.inventario_id' => 'required|exists:inventario,id',
            'items.*.cantidad' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            $totalVenta = 0;

            // Calcular total y verificar stock
            foreach ($request->items as $item) {
                $inventario = Inventario::findOrFail($item['inventario_id']);
                
                if ($inventario->stock < $item['cantidad']) {
                    throw new \Exception("Stock insuficiente para {$inventario->nombre_item}");
                }

                $totalVenta += $inventario->precio * $item['cantidad'];
            }

            // Crear venta
            $venta = Venta::create([
                'miembro_id' => $request->miembro_id,
                'total_venta' => $totalVenta,
                'metodo_pago' => $request->metodo_pago,
                'referencia_pago' => $request->referencia_pago,
            ]);

            // Crear detalles y actualizar stock
            foreach ($request->items as $item) {
                $inventario = Inventario::findOrFail($item['inventario_id']);

                VentaDetalle::create([
                    'venta_id' => $venta->id,
                    'inventario_id' => $item['inventario_id'],
                    'cantidad' => $item['cantidad'],
                    'precio_unitario' => $inventario->precio,
                ]);

                $inventario->decrement('stock', $item['cantidad']);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Venta registrada con éxito.',
                'venta_id' => $venta->id
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function ventasDelDia()
    {
        $ventas = Venta::with(['miembro', 'detalles.inventario'])
            ->whereDate('fecha_venta', today())
            ->get();

        $totalVentas = $ventas->sum('total_venta');

        return response()->json([
            'success' => true,
            'data' => $ventas,
            'total' => $totalVentas
        ]);
    }
}
