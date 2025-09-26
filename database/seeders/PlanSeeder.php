<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $old_planes = DB::connection('mysql_old')->table('planes')->get();

        foreach ($old_planes as $plan) {
            Plan::create([
                'id' => $plan->id,
                'nombre_plan' => $plan->nombre_plan,
                'descripcion' => $plan->descripcion,
                'precio_base' => $plan->precio_base,
                'duracion_dias' => $plan->duracion_dias,
                'estatus' => $plan->estatus,
            ]);
        }
    }
}
