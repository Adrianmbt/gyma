<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('miembros', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->string('telefono', 30);
            $table->string('numero_cedula', 20)->unique();
            $table->date('fecha_nacimiento');
            $table->string('ruta_foto')->nullable()->default('uploads/default.png');
            $table->enum('estatus', ['activo', 'inactivo', 'vetado'])->default('activo');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('miembros');
    }
};
