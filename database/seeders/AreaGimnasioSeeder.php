<?php

namespace Database\Seeders;

use App\Models\AreaGimnasio;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AreaGimnasioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $old_areas = DB::connection('mysql_old')->table('areas_gimnasio')->get();

        foreach ($old_areas as $area) {
            AreaGimnasio::create([
                'id' => $area->id,
                'nombre_area' => $area->nombre_area,
                'ubicacion' => $area->ubicacion,
            ]);
        }
    }
}
