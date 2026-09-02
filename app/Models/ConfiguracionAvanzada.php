<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ConfiguracionAvanzada extends Model
{
    use hasFactory;

    protected $table = "configuracion_avanzada";

    protected $fillable = [
        "id_institucion",
        "limite_solicitudes_prestamos",
        "limite_solicitudes_computo",
        "limite_bloqueo"
    ];

    public function institucion(){
        return $this->belongsTo(Institucion::class, 'id_institucion');
    }
}
