<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class InformacionGruposExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithEvents, WithChunkReading
{
    protected $idInstitucion;

    protected $mensajesModales = [];

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
        return DB::table('grupos as g')
            ->leftJoin('grupo_laboratorios as gl','gl.id_grupo','=','g.id')
            ->leftJoin('laboratorios as l','l.id','=','gl.id_laboratorio')
            ->select(
                'g.nombre',
                'g.tipo_grupo',
                DB::raw("STRING_AGG(l.nombre::text, '\n- ') as lista_laboratorios")
            )
            ->where('g.id_institucion', '=', $this->idInstitucion)
            ->groupBy('g.id', 'g.nombre', 'g.tipo_grupo')
            ->orderBy('g.tipo_grupo','asc')
            ->orderBy('g.nombre','asc')
        ;
    }

    /**
    * Definición de los encabezados del reporte de Excel
    */
    public function headings(): array
    {
        return [
            'Nombre del Grupo',
            'Tipo Grupo',
            'Laboratorios'
        ];
    }

    /**
    * Transformación de cada fila de la consulta
    * @param mixed $grupo
    */
    public function map($grupo): array
    {
        $numeroFilaActual = count($this->mensajesModales) + 2;

        $laboratorios = $grupo->lista_laboratorios ? "- " . $grupo->lista_laboratorios : 'Ninguno asignado';
        
        $this->mensajesModales[$numeroFilaActual] = "Laboratorios asignados a este grupo:\n" . $laboratorios;

        return [
            $grupo->nombre,
            $grupo->tipo_grupo,
            'Ver laboratorios'
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow(); 
                
                for ($row=2;$row<=$highestRow;$row++) {
                    $cellCoordinate = 'C' . $row; 
                    
                    $sheet->getStyle($cellCoordinate)->getFont()->getColor()->setARGB('FF7B1FA3');

                    $validation = $sheet->getCell($cellCoordinate)->getDataValidation();
                    $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_NONE);
                    $validation->setShowInputMessage(true);
                    
                    $validation->setPromptTitle('Laboratorios con acceso');
                    $textoModal = $this->mensajesModales[$row] ?? 'No hay información disponible.';
                    $validation->setPrompt($textoModal);
                }
            },
        ];
    }
}
