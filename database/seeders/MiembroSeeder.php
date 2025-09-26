<?php

namespace Database\Seeders;

use App\Models\Miembro;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MiembroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $old_miembros = DB::connection('mysql_old')->table('miembros')->get();

        foreach ($old_miembros as $miembro) {
            Miembro::create([
                'id' => $miembro->id,
                'nombre' => $miembro->nombre,
                'telefono' => $miembro->telefono,
                'numero_cedula' => $miembro->numero_cedula,
                'fecha_nacimiento' => $miembro->fecha_nacimiento,
                'ruta_foto' => $miembro->ruta_foto,
                'fecha_registro' => $miembro->fecha_registro,
                'estatus' => $miembro->estatus,
            ]);
        }
    }
}
