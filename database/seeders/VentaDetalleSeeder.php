<?php

namespace Database\Seeders;

use App\Models\VentaDetalle;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VentaDetalleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $old_venta_detalles = DB::connection('mysql_old')->table('venta_detalles')->get();

        foreach ($old_venta_detalles as $detalle) {
            VentaDetalle::create([
                'id' => $detalle->id,
                'venta_id' => $detalle->venta_id,
                'inventario_id' => $detalle->inventario_id,
                'cantidad' => $detalle->cantidad,
                'precio_unitario' => $detalle->precio_unitario,
            ]);
        }
    }
}
