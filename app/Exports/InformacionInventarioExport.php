<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class InformacionInventarioExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithChunkReading
{
    protected $id;
    protected $tipo;

    // Recibimos el ID de la institución por el constructor para evitar usar session() directo aquí
    public function __construct($tipo, $id)
    {
        $this->tipo = $tipo;
        $this->id = $id;
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
        $columna = '';
        if ($this->tipo == 'institucion') $columna = 'l.id_institucion';
        else $columna = 'i.id_laboratorio';
        return DB::table('inventarios as i')
            ->join('laboratorios as l','l.id','=','i.id_laboratorio')
            ->join('materiales as m','m.id','=','i.id_material')
            ->select(
                'm.nombre as nombreMaterial',
                'l.nombre as nombreLaboratorio',
                'i.cantidad_disponible',
                'i.cantidad_total'
            )
            ->where($columna,'=',$this->id)
            ->orderBy('l.nombre','asc')
            ->orderBy('m.nombre','asc');
    }

    /**
    * Definición de los encabezados del reporte de Excel
    */
    public function headings(): array
    {
        return [
            'Nombre del Material',
            'Nombre del Laboratorio',
            'Cantidad Disponible',
            'Cantidad Total'
        ];
    }

    /**
    * Transformación de cada fila de la consulta
    * @param mixed $inventario
    */
    public function map($inventario): array
    {

        return [
            $inventario->nombreMaterial,
            $inventario->nombreLaboratorio,
            $inventario->cantidad_disponible,
            $inventario->cantidad_total
        ];
    }
}
