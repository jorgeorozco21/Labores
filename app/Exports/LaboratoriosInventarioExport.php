<?php

namespace App\Exports;

use App\Models\Laboratorio;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;

class LaboratoriosInventarioExport implements FromCollection, WithTitle
{
    public function title(): string
    {
        return 'Laboratorios';
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Laboratorio::where('id_institucion','=',session('id_institucion'))->whereIn('tipo', ['prestamos', 'mixto'])->get()->map(function ($laboratorio){
            return [
                'id' => $laboratorio->id,
                'nombre' => $laboratorio->nombre
            ];
        });
    }
}
