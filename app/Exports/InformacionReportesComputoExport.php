<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\RichText\RichText;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class InformacionReportesComputoExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithEvents, WithChunkReading
{
    protected $idLaboratorio;

    protected $tarjetasDinamicas = [];

    // Recibimos el ID de la institución por el constructor para evitar usar session() directo aquí
    public function __construct($idLaboratorio)
    {
        $this->idLaboratorio = $idLaboratorio;
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
        $subAuditorias = 
            DB::table('solicitudes_computo as sc')
            ->join('computadoras as c','c.id','=','sc.id_computadora')
            ->leftJoin('auditoria_computo as ac','ac.id_solicitud','=','sc.id')
            ->select(
                'sc.id as id_reporte',
                'sc.id_computadora',
                'sc.tipo',
                'sc.descripcion',
                'sc.created_at',
                DB::raw("STRING_AGG(
                        CONCAT(
                            ' - Nombre: ', ac.info_usuario->>'nombre', 
                            ' (', ac.info_usuario->>'email', ') ', 
                            ' - Estado: ', UPPER(ac.estado), 
                            ' - Fecha: ', TO_CHAR(ac.created_at, 'DD/MM/YYYY HH24:MI:SS')
                        ), 
                        '\n'
                    ) as lista_auditorias")
            )
            ->where('c.id_laboratorio','=',$this->idLaboratorio)
            ->groupBy('sc.id_computadora', 'sc.id', 'sc.tipo', 'sc.descripcion', 'sc.created_at')
        ;

        $subEspecificaciones = 
            DB::table('computadoras as c')
            ->join('plantillas_computo as pc', 'pc.id_laboratorio', '=', 'c.id_laboratorio')
            ->leftJoin('especificaciones_equipos_computo as eec', function($join) {
                $join->on('eec.id_plantilla', '=', 'pc.id')
                    ->on('eec.id_computadora', '=', 'c.id');
            })
            ->select(
                'c.id as id_computadora',
                DB::raw("JSONB_AGG(
                    JSONB_BUILD_OBJECT(
                        'id_plantilla', pc.id,
                        'nombre', pc.nombre,
                        'descripcion', eec.descripcion
                    )
                ) as lista_especificaciones")
            )
            ->where('c.id_laboratorio', '=', $this->idLaboratorio)
            ->groupBy('c.id')
        ;

        return DB::table('computadoras as c')
            ->leftJoinSub($subAuditorias, 'rep', 'rep.id_computadora', '=', 'c.id')
            ->leftJoinSub($subEspecificaciones, 'eec', 'eec.id_computadora', '=', 'c.id')
            ->select(
                'c.id',
                'c.numero_computadora',
                'c.estado',
                DB::raw('COUNT(DISTINCT rep.id_reporte) as cantidad_reportes'),
                DB::raw("JSONB_AGG(
                    JSONB_BUILD_OBJECT(
                        'id', rep.id_reporte,
                        'tipo', rep.tipo,
                        'descripcion', rep.descripcion,
                        'created_at', rep.created_at,
                        'auditorias', rep.lista_auditorias
                    )
                ) FILTER (WHERE rep.id_reporte IS NOT NULL) as reportes_json"),
                DB::raw("COALESCE(eec.lista_especificaciones, '[]'::jsonb) as especificaciones_json")
            )
            ->where('c.id_laboratorio', '=', $this->idLaboratorio)
            ->groupBy('c.id', 'c.numero_computadora', 'c.estado', 'eec.lista_especificaciones')
            ->orderBy('c.id', 'asc');
    }

    /**
    * Definición de los encabezados del reporte de Excel
    */
    public function headings(): array
    {
        $encabezados = ['Numero de Computadora','Estado'];

        $plantillas = 
            DB::table('plantillas_computo as pc')
            ->select(
                'pc.id',
                'pc.nombre'
            )
            ->where('pc.id_laboratorio','=',$this->idLaboratorio)
            ->orderBy('pc.id','asc')
            ->get()
        ;

        $this->plantillasLaboratorio = $plantillas;

        foreach ($plantillas as $plantilla){
            $encabezados[] = $plantilla->nombre;
        }

        $encabezados[] = 'Cantidad de Reportes';

        return $encabezados;
    }

    /**
    * Transformación de cada fila de la consulta
    * @param mixed $computadora
    */
    public function map($computadora): array
    {
        $fila = [
            $computadora->numero_computadora,
            strtoupper($computadora->estado)
        ];

        $especificaciones = json_decode($computadora->especificaciones_json ?? '[]');

        $valoresSpecs = [];

        if (!empty($especificaciones)){
            foreach ($especificaciones as $especificacion){
                $valoresSpecs[$especificacion->id_plantilla] = $especificacion->descripcion ?? '';
            }
        }

        $indiceColumnaActual = 2;

        foreach ($this->plantillasLaboratorio as $plantilla){
            $fila[] = $valoresSpecs[$plantilla->id] ?? '';
            $indiceColumnaActual++;
        }

        $fila[] = $computadora->cantidad_reportes == 0 ? 'Sin reportes registrados' : $computadora->cantidad_reportes;

        $numeroFilaActual = count($this->tarjetasDinamicas) + 2;
        $this->tarjetasDinamicas[$numeroFilaActual] = [];

        $reportes = json_decode($computadora->reportes_json ?? '[]');

        if (!empty($reportes)){
            foreach ($reportes as $rep){
                $columnaIndexArray = count($fila);

                $fila[] = "Reporte #" . $rep->id;

                $historialAuditorias = $rep->auditorias ? $rep->auditorias : "Sin movimientos de auditoría.";
                
                $textoTarjeta = "Tipo: " . $rep->tipo . "\n" .
                                "Descripcion: " . $rep->descripcion . "\n" .
                                "Fecha: " . $rep->created_at . "\n" . 
                                "HISTORIAL DE AUDITORÍA:\n" . $historialAuditorias;

                $this->tarjetasDinamicas[$numeroFilaActual][$columnaIndexArray] = $textoTarjeta;
            }
        }

        return $fila;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow(); 
                
                for ($row=2;$row<=$highestRow;$row++){
                    
                    if (isset($this->tarjetasDinamicas[$row]) && !empty($this->tarjetasDinamicas[$row])){
                        
                        foreach ($this->tarjetasDinamicas[$row] as $colIndex => $contenidoTarjeta) {
                            
                            $letraColumna = Coordinate::stringFromColumnIndex($colIndex + 1);
                            $celdaObjetivo = $letraColumna . $row;

                            $sheet->getStyle($celdaObjetivo)->getFont()->setBold(true);
                            $sheet->getStyle($celdaObjetivo)->getFont()->getColor()->setARGB('FF7B1FA3');

                            $comment = $sheet->getComment($celdaObjetivo);
                            $richText = new RichText();
                            
                            $titulo = $richText->createTextRun("DETALLES DEL REPORTE\n");
                            $titulo->getFont()->setBold(true);
                            $titulo->getFont()->setSize(10);

                            $cuerpo = $richText->createTextRun($contenidoTarjeta);
                            $cuerpo->getFont()->setBold(false);
                            $cuerpo->getFont()->setSize(9.5);
                            $cuerpo->getFont()->setColor(new Color('333333'));

                            $comment->setText($richText);

                            $textoCompleto = "DETALLES DEL REPORTE\n" . $contenidoTarjeta;
                            $lineas = explode("\n", $textoCompleto);
                            $cantLineas = count($lineas);
                            
                            $maxCarac = 0;
                            foreach ($lineas as $linea) {
                                $maxCarac = max($maxCarac, mb_strlen($linea));
                            }

                            $ancho = max(240, min(500, ($maxCarac * 6.8) + 30)); 
                            $alto = ($cantLineas * 16) + 35;

                            $comment->setWidth($ancho); 
                            $comment->setHeight($alto);
                        }
                    }
                }
            },
        ];
    }
}
