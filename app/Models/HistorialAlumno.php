<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistorialAlumno extends Model
{
    use HasFactory;

    protected $table = "historial_alumnos";

    protected $fillable = [
        'id_solicitud',
        'info_usuario',
        'info_material',
        'info_auditoria',
        'estado',
        'descripcion'
    ];

    protected $casts = [
        "info_material" => "array",
        "info_usuario" => "array",
        "info_auditoria" => "array"
    ];
}
