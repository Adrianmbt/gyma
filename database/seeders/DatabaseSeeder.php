<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UsuarioSeeder::class,
            AreaGimnasioSeeder::class,
            EntrenadorSeeder::class,
            InventarioSeeder::class,
            MiembroSeeder::class,
            PlanSeeder::class,
            PromocionSeeder::class,
            VentaSeeder::class,
            VentaDetalleSeeder::class,
            MiembroSuscripcionSeeder::class,
        ]);
    }
}
