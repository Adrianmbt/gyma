<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\Inventario;
use App\Models\VentaDetalle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VentaController extends Controller
{
    /**
     * Listar productos disponibles para la venta.
     */
    public function getProductosParaVenta()
    {
        $productos = Inventario::where('tipo', 'Tienda')
            ->where('stock', '>', 0)
            ->orderBy('nombre_item')
            ->get(['id', 'nombre_item', 'precio', 'stock']);
        
        return response()->json(['success' => true, 'data' => $productos]);
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
            'total_venta' => 'required|numeric|min:0.01',
            'carrito' => 'required|array|min:1',
            'carrito.*.id' => 'required|integer|exists:inventario,id',
            'carrito.*.cantidad' => 'required|integer|min:1',
            'carrito.*.precio' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // 1. Crear la venta
            $venta = Venta::create([
                'miembro_id' => $validated['miembro_id'],
                'total_venta' => $validated['total_venta'],
            ]);

            // 2. Procesar cada item del carrito
            foreach ($validated['carrito'] as $item) {
                // Verificar stock
                $producto = Inventario::find($item['id']);
                if ($producto->stock < $item['cantidad']) {
                    throw new \Exception("Stock insuficiente para el producto: {$producto->nombre_item}. Disponible: {$producto->stock}");
                }

                // Crear el detalle de la venta
                VentaDetalle::create([
                    'venta_id' => $venta->id,
                    'inventario_id' => $item['id'],
                    'cantidad' => $item['cantidad'],
                    'precio_unitario' => $item['precio'],
                ]);

                // Actualizar el stock
                $producto->decrement('stock', $item['cantidad']);
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Venta registrada con éxito.', 'data' => $venta], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Error al registrar la venta: ' . $e->getMessage()], 500);
        }
    }

    // Otros métodos del resource controller (index, show, update, destroy) pueden ser implementados si es necesario.
    // Por ejemplo, para ver el historial de ventas.
    public function index()
    {
        // Cargar ventas con sus detalles y el nombre del miembro
        $ventas = Venta::with(['detalles.producto', 'miembro:id,nombre'])->orderBy('fecha_venta', 'desc')->get();
        return response()->json(['success' => true, 'data' => $ventas]);
    }

    public function show(Venta $venta)
    {
        $venta->load(['detalles.producto', 'miembro:id,nombre']);
        return response()->json(['success' => true, 'data' => $venta]);
    }
}
