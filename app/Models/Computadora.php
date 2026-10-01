<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Computadora extends Model
{
    use HasFactory;

    protected $table = "computadoras";

    protected $fillable = [
        "numero_computadora",
        "estado",
        "id_laboratorio"
    ];

    public function laboratorio(){
        return $this->belongsTo(Laboratorio::class, 'id_laboratorio');
    }

    public function especificacionesComputo()
    {
        return $this->hasMany(EspecificacionComputo::class, 'id_computadora');
    }
}
