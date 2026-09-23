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
        Schema::create('historial_alumnos', function (Blueprint $table) {
            $table->id();
            $table->string("id_solicitud");
            $table->jsonb("info_usuario");
            $table->jsonb("info_auditoria");
            $table->jsonb("info_material");
            $table->string("estado");
            $table->text("descripcion");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historial_alumnos');
    }
};
