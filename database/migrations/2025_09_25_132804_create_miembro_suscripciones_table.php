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
        Schema::create('miembro_suscripciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('miembro_id')->constrained('miembros')->onDelete('cascade');
            $table->foreignId('plan_id')->constrained('planes');
            $table->foreignId('promocion_id')->nullable()->constrained('promociones');
            $table->foreignId('entrenador_id')->nullable()->constrained('entrenadores');
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->decimal('monto_pagado', 10, 2);
            $table->string('metodo_pago', 50)->nullable();
            $table->string('referencia_pago', 100)->nullable();
            $table->timestamp('fecha_registro')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('miembro_suscripciones');
    }
};
