<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Usuario;
use App\Models\Grupo;
use App\Models\Laboratorio;
use App\Models\Material;

class Institucion extends Model
{
    use HasFactory;

    protected $table = "instituciones";

    protected $fillable = [
        "nombre",
        "clave",
        "tag"
    ];

    public function usuarios(){
        return $this->hasMany(Usuario::class, 'id_institucion');
    }

    public function grupos(){
        return $this->hasMany(Grupo::class, 'id_institucion');
    }

    public function laboratorios(){
        return $this->hasMany(Laboratorio::class, "id_institucion");
    }

    public function materiales(){
        return $this->hasMany(Material::class, "id_institucion");
    }

    public function configuracionAvanzada(){
        return $this->hasOne(ConfiguracionAvanzada::class, 'id_institucion');
    }

    
}
