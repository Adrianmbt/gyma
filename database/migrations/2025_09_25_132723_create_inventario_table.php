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
        Schema::create('inventario', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_item', 50)->unique();
            $table->string('nombre_item', 150);
            $table->text('descripcion')->nullable();
            $table->enum('tipo', ['Tienda', 'Operaciones']);
            $table->string('departamento', 100);
            $table->integer('stock')->default(1);
            $table->decimal('precio', 10, 2)->default(0.00);
            $table->foreignId('id_area')->nullable()->constrained('areas_gimnasio')->onDelete('set null');
            $table->string('estado', 50)->default('Operativo');
            $table->date('fecha_adquisicion')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventario');
    }
};
