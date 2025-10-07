<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;
use App\Models\AreaGimnasio;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Crear usuario admin por defecto
        Usuario::create([
            'nombre' => 'Administrador',
            'cedula' => 'V00000000',
            'telefono' => '0000-0000000',
            'usuario' => 'admin',
            'clave' => Hash::make('admin123'),
            'rol' => 'admin',
        ]);

        // Crear áreas del gimnasio
        AreaGimnasio::create([
            'nombre_area' => 'Tienda / Recepción',
            'ubicacion' => 'Entrada Principal',
        ]);

        AreaGimnasio::create([
            'nombre_area' => 'Equipamiento / Operaciones',
            'ubicacion' => 'Sala Principal',
        ]);

        AreaGimnasio::create([
            'nombre_area' => 'Cardio y Funcional',
            'ubicacion' => 'Planta Alta',
        ]);
    }
}
