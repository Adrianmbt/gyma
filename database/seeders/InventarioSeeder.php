<?php

namespace Database\Seeders;

use App\Models\Inventario;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InventarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $old_inventario = DB::connection('mysql_old')->table('inventario')->get();

        foreach ($old_inventario as $item) {
            Inventario::create([
                'id' => $item->id,
                'codigo_item' => $item->codigo_item,
                'nombre_item' => $item->nombre_item,
                'descripcion' => $item->descripcion,
                'tipo' => $item->tipo,
                'departamento' => $item->departamento,
                'stock' => $item->stock,
                'precio' => $item->precio,
                'id_area' => $item->id_area,
                'estado' => $item->estado,
                'fecha_adquisicion' => $item->fecha_adquisicion,
            ]);
        }
    }
}
