<?php

namespace Database\Seeders;

use App\Models\Usuario;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $old_usuarios = DB::connection('mysql_old')->table('usuarios')->get();

        foreach ($old_usuarios as $usuario) {
            Usuario::create([
                'id' => $usuario->id,
                'nombre' => $usuario->nombre,
                'cedula' => $usuario->cedula,
                'telefono' => $usuario->telefono,
                'usuario' => $usuario->usuario,
                'clave' => $usuario->clave,
                'rol' => $usuario->rol,
            ]);
        }
    }
}
