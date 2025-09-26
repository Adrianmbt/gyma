<?php

namespace Database\Seeders;

use App\Models\Promocion;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PromocionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $old_promociones = DB::connection('mysql_old')->table('promociones')->get();

        foreach ($old_promociones as $promocion) {
            Promocion::create([
                'id' => $promocion->id,
                'nombre_promo' => $promocion->nombre_promo,
                'descripcion' => $promocion->descripcion,
                'tipo_descuento' => $promocion->tipo_descuento,
                'valor_descuento' => $promocion->valor_descuento,
                'aplica_a' => $promocion->aplica_a,
                'condicion_personas' => $promocion->condicion_personas,
                'fecha_inicio' => $promocion->fecha_inicio,
                'fecha_fin' => $promocion->fecha_fin,
                'estatus' => $promocion->estatus,
            ]);
        }
    }
}
