<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promociones', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_promo', 150);
            $table->text('descripcion')->nullable();
            $table->enum('tipo_descuento', ['porcentaje', 'precio_fijo']);
            $table->decimal('valor_descuento', 10, 2);
            $table->enum('aplica_a', ['suscripcion', 'producto'])->default('suscripcion');
            $table->integer('condicion_personas')->default(1);
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->enum('estatus', ['activa', 'inactiva'])->default('activa');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('promociones');
    }
};
