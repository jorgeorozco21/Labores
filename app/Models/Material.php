<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Institucion;
use App\Models\Inventario;

class Material extends Model
{
    
    use HasFactory;

    protected $table = "materiales";

    protected $fillable = [
        "nombre",
        "descripcion",
        "tipo_prestamo",
        "tipo_devolucion",
        "id_institucion"
    ];

    public function institucion(){
        return $this->belongsTo(Institucion::class, "id_institucion");
    }

    public function inventarios(){
        return $this->hasMany(Inventario::class, "id_material");
    }

}
