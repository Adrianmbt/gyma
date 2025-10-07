<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('areas_gimnasio', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_area', 100)->unique();
            $table->string('ubicacion', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('areas_gimnasio');
    }
};
