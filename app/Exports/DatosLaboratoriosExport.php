<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Style\Protection;

class DatosLaboratoriosExport implements FromCollection, WithTitle, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return collect([
            ['nombre','tipo','cantidad_computadoras']
        ]);
    }

    public function title(): string
    {
        return 'Datos';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $evento){
                $hoja = $evento->sheet->getDelegate();

                for ($fila = 2; $fila <= 1000; $fila++) {
                    $validarTipo = new DataValidation();
                    $validarTipo->setType(DataValidation::TYPE_LIST)
                                ->setErrorStyle(DataValidation::STYLE_STOP) 
                                ->setAllowBlank(false)
                                ->setShowInputMessage(true)
                                ->setShowErrorMessage(true) 
                                ->setShowDropDown(true)
                                ->setErrorTitle('Dato no válido')
                                ->setError('Por favor, selecciona una opción de la lista.')
                                ->setFormula1('"prestamos,computo,mixto"');

                    $hoja->getCell("B{$fila}")->setDataValidation($validarTipo);

                    $hoja->setCellValue("B{$fila}", 'prestamos');
                }

                $hoja->getColumnDimension('A')->setWidth(40);
                $hoja->getColumnDimension('B')->setWidth(20); 
                $hoja->getColumnDimension('C')->setWidth(20);
                
                // 1. Configurar la protección
                $proteccion = $hoja->getProtection();
                $proteccion->setSheet(true); 

                // 3. Desbloquear el rango de entrada de datos (A2:C1000)
                $hoja->getStyle('A2:C1000')->getProtection()->setLocked(Protection::PROTECTION_UNPROTECTED);
            }
        ];
    }
}
