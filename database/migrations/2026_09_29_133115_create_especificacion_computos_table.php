<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('especificaciones_equipos_computo', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_plantilla');
            $table->unsignedBigInteger('id_computadora');
            $table->text('descripcion');
            $table->timestamps();

            $table->foreign('id_plantilla')->references('id')->on('plantillas_computo')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id_computadora')->references('id')->on('computadoras')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('especificacion_computos');
    }
};
