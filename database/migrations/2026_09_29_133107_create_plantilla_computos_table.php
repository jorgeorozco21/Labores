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
        Schema::create('plantillas_computo', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_laboratorio');
            $table->string('nombre');
            $table->timestamps();

            $table->foreign('id_laboratorio')->references('id')->on('laboratorios')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plantilla_computos');
    }
};
