<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EspecificacionComputo extends Model
{
    use HasFactory;

    protected $table = 'especificaciones_equipos_computo';

    protected $fillable = [
        'id_pantilla',
        'id_computadora',
        'descripcion'
    ];

    public function plantillaComputo()
    {
        return $this->belongsTo(PlantillaComputo::class, 'id_plantilla');
    }

    public function computadora()
    {
        return $this->belongsTo(Computadora::class, 'id_computadora');
    }
}
