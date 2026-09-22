<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Institucion;
use App\Models\Usuario;
use App\Models\ConfiguracionAvanzada;
use App\Models\Servicios;
use Illuminate\Support\Facades\Hash;

class InfoBaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $servicio = Servicios::create(
            [
                "gestor_laboratorio" => "1"
            ]
        );

        Institucion::create(
            [
                "nombre" => "Escuela",
                "clave" => "1234",
                "tag" => "escuela",
                "id_servicio" => $servicio->id
            ]
        );

        ConfiguracionAvanzada::create(
            [
                "id_institucion" => Institucion::where("nombre", "Escuela")->first()->id,
                "limite_solicitudes_prestamos" => -1,
                "limite_solicitudes_computo" => -1,
                "limite_bloqueo" => -1
            ]
        );

        Usuario::create(
            [
                "nombre_usuario" => "escuela",
                "email" => "prueba@gmail.com",
                "contrasena" => Hash::make("hola"),
                "nombre" => "Jonathan Orozco",
                "admin" => "1",
                "mantenimiento" => "0",
                "encargado" => "0",
                "normal" => "0",
                "id_grupo" => null,
                "id_institucion" => Institucion::where("nombre", "Escuela")->first()->id
            ]
        );

        Usuario::create(
            [
                "nombre_usuario" => "labores",
                "email" => "hola.labores.web@gmail.com",
                "contrasena" => Hash::make("hola"),
                "nombre" => "Labores Web",
                "admin" => "1",
                "mantenimiento" => "0",
                "encargado" => "0",
                "normal" => "0",
                "id_grupo" => null,
                "id_institucion" => null
            ]
        );
    }
}
