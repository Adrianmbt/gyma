<?php

namespace Database\Seeders;

use App\Models\Entrenador;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EntrenadorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $old_entrenadores = DB::connection('mysql_old')->table('entrenadores')->get();

        foreach ($old_entrenadores as $entrenador) {
            Entrenador::create([
                'id' => $entrenador->id,
                'nombre_completo' => $entrenador->nombre_completo,
                'numero_cedula' => $entrenador->numero_cedula,
                'telefono' => $entrenador->telefono,
                'email' => $entrenador->email,
                'especialidad' => $entrenador->especialidad,
                'costo_mensual' => $entrenador->costo_mensual,
                'estatus' => $entrenador->estatus,
                'ruta_foto' => $entrenador->ruta_foto,
            ]);
        }
    }
}
