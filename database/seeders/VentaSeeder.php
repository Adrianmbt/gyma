<?php

namespace Database\Seeders;

use App\Models\Venta;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VentaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $old_ventas = DB::connection('mysql_old')->table('ventas')->get();

        foreach ($old_ventas as $venta) {
            Venta::create([
                'id' => $venta->id,
                'miembro_id' => $venta->miembro_id,
                'fecha_venta' => $venta->fecha_venta,
                'total_venta' => $venta->total_venta,
                'metodo_pago' => $venta->metodo_pago,
                'referencia_pago' => $venta->referencia_pago,
            ]);
        }
    }
}
