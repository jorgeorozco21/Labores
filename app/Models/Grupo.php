<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Institucion;

class Grupo extends Model
{
    use HasFactory;

    protected $table = "grupos";

    protected $fillable = [
        "nombre",
        "tipo_grupo",
        "id_institucion"
    ];

    public function institucion(){
        return $this->belongsTo(Institucion::class, 'id_institucion');
    }

    public function grupoLaboratorio(){
        return $this->hasMany(GrupoLaboratorio::class, 'id_grupo');
    }
}
