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
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string("nombre_usuario")->unique();
            $table->string("email");
            $table->string("contrasena");
            $table->string("nombre");
            $table->string("admin")->nullable();
            $table->string("mantenimiento");
            $table->string("encargado");
            $table->string("normal");
            $table->unsignedBigInteger("id_grupo")->nullable();
            $table->unsignedBigInteger("id_institucion")->nullable();
            $table->timestamps();

            $table->foreign("id_grupo")->references("id")->on("grupos");
            $table->foreign("id_institucion")->references("id")->on("instituciones")->onDelete("cascade")->onUpdate("cascade");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
