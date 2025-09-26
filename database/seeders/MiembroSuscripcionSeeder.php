<?php

namespace Database\Seeders;

use App\Models\MiembroSuscripcion;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MiembroSuscripcionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $old_suscripciones = DB::connection('mysql_old')->table('miembro_suscripciones')->get();

        foreach ($old_suscripciones as $suscripcion) {
            MiembroSuscripcion::create([
                'id' => $suscripcion->id,
                'miembro_id' => $suscripcion->miembro_id,
                'plan_id' => $suscripcion->plan_id,
                'promocion_id' => $suscripcion->promocion_id,
                'entrenador_id' => $suscripcion->entrenador_id,
                'fecha_inicio' => $suscripcion->fecha_inicio,
                'fecha_fin' => $suscripcion->fecha_fin,
                'monto_pagado' => $suscripcion->monto_pagado,
                'metodo_pago' => $suscripcion->metodo_pago,
                'referencia_pago' => $suscripcion->referencia_pago,
                'fecha_registro' => $suscripcion->fecha_registro,
            ]);
        }
    }
}
