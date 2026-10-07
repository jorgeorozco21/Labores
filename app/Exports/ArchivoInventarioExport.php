<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeWriting;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ArchivoInventarioExport implements WithMultipleSheets, WithEvents
{
    public function sheets(): array
    {
        return [
            "Datos" => new DatosInventarioExport(),
            "Materiales" => new MaterialesInventarioExport(),
        ];
    }

    public function registerEvents(): array
    {
        return [
            BeforeWriting::class => function (BeforeWriting $event) {

                $spreadsheet = $event->writer->getDelegate();

                $materiales = $spreadsheet->getSheetByName('Materiales');
                $laboratorios = $spreadsheet->getSheetByName('Laboratorios');

                if ($materiales) {
                    $materiales->setSheetState(Worksheet::SHEETSTATE_VERYHIDDEN);
                }

                if ($laboratorios) {
                    $laboratorios->setSheetState(Worksheet::SHEETSTATE_VERYHIDDEN);
                }
            }
        ];
    }
}
