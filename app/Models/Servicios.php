<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Servicios extends Model
{
    use HasFactory;

    protected $table = 'servicios';

    protected $fillable = [
        'gestor_laboratorio'
    ];

    public function instituciones(){
        return $this->belongsTo(Institucion::class, 'id_servicio');
    }
}
