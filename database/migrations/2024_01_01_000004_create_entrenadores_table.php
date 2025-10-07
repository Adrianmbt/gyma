<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('entrenadores', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_completo', 200);
            $table->string('numero_cedula', 20)->unique();
            $table->string('telefono', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('especialidad', 150)->nullable();
            $table->decimal('costo_mensual', 10, 2)->default(0);
            $table->enum('estatus', ['activo', 'inactivo'])->default('activo');
            $table->string('ruta_foto')->default('uploads/default.png');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('entrenadores');
    }
};
