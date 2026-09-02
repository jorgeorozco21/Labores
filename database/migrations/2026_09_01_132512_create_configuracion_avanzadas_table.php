<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('configuracion_avanzada', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_institucion');
            $table->integer('limite_solicitudes_prestamos');
            $table->integer('limite_solicitudes_computo');
            $table->integer('limite_bloqueo');
            $table->timestamps();

            $table->foreign('id_institucion')->references('id')->on('instituciones')->onDelete('cascade');
        });

        $idInstituciones = DB::table('instituciones')->pluck('id');

        $configuracionesIniciales = [];

        foreach ($idInstituciones as $id){
            $configuracionesIniciales[] = [
                'id_institucion' => $id,
                'limite_solicitudes_prestamos' => -1,
                'limite_solicitudes_computo' => -1,
                'limite_bloqueo' => -1,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        if (!empty($configuracionesIniciales)){
            DB::table('configuracion_avanzada')->insert($configuracionesIniciales);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('configuracion_avanzada');
    }
};
