<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlantillaComputo extends Model
{
    use HasFactory;

    protected $table = "plantillas_computo";

    protected $fillable = [
        'id_laboratorio',
        'nombre'
    ];

    public function laboratorio()
    {
        return $this->belongsTo(Laboratorio::class, 'id_laboratorio');
    }

    public function especificacionComputo()
    {
        return $this->hasMany(EspecificacionComputo::class, 'id_plantilla');
    }
}
