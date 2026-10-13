<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class InformacionMaterialesExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithChunkReading
{
    protected $idInstitucion;

    // Recibimos el ID de la institución por el constructor para evitar usar session() directo aquí
    public function __construct($idInstitucion)
    {
        $this->idInstitucion = $idInstitucion;
    }

    public function chunkSize(): int
    {
        return 200; 
    }

    /**
    * Retorna el Query Builder de la consulta (AHORA SÍ, SIN ->get())
    */
    public function query()
    {
        return DB::table('materiales as m')
            ->select(
                'm.nombre',
                'm.descripcion',
                'm.tipo_prestamo',
                'm.tipo_devolucion'
            )
            ->where('m.id_institucion', '=', $this->idInstitucion)
            ->orderBy("m.tipo_prestamo","ASC")
            ->orderBy("m.tipo_devolucion","ASC")
            ->orderBy("m.nombre","ASC")
            ->orderBy("m.created_at","DESC")
        ;
    }

    /**
    * Definición de los encabezados del reporte de Excel
    */
    public function headings(): array
    {
        return [
            'Nombre del Material',
            'Descripción',
            'Tipo de Prestamo',
            'Tipo de Devolucion'
        ];
    }

    /**
    * Transformación de cada fila de la consulta
    * @param mixed $material
    */
    public function map($material): array
    {

        return [
            $material->nombre,
            $material->descripcion,
            $material->tipo_prestamo,
            $material->tipo_devolucion
        ];
    }
}
