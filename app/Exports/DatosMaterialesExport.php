<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Style\Protection;

class DatosMaterialesExport implements FromCollection, WithEvents, WithTitle
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return collect([
            ['nombre','descripcion','tipo_prestamo','tipo_devolucion']
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
                $id_institucion = session('id_institucion');

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
                                ->setFormula1('"prestamos por unidad,prestamos por cantidad"');

                    $hoja->getCell("C{$fila}")->setDataValidation($validarTipo);

                    $hoja->setCellValue("C{$fila}", 'prestamos por unidad');

                    $validarDevolucion = new DataValidation();
                    $validarDevolucion->setType(DataValidation::TYPE_LIST)
                                ->setErrorStyle(DataValidation::STYLE_STOP) 
                                ->setAllowBlank(false)
                                ->setShowInputMessage(true)
                                ->setShowErrorMessage(true) 
                                ->setShowDropDown(true)
                                ->setErrorTitle('Dato no válido')
                                ->setError('Por favor, selecciona una opción de la lista.')
                                ->setFormula1('"retornable,consumible"');

                    $hoja->getCell("D{$fila}")->setDataValidation($validarDevolucion);

                    $hoja->setCellValue("D{$fila}", 'retornable');
                }

                $hoja->getColumnDimension('A')->setWidth(40);
                $hoja->getColumnDimension('B')->setWidth(60); 
                $hoja->getColumnDimension('C')->setWidth(40);
                $hoja->getColumnDimension('D')->setWidth(40);
                
                // 1. Configurar la protección
                $proteccion = $hoja->getProtection();
                $proteccion->setSheet(true); 

                // 3. Desbloquear el rango de entrada de datos (A2:C1000)
                $hoja->getStyle('A2:D1000')->getProtection()->setLocked(Protection::PROTECTION_UNPROTECTED);
            }
        ];
    }
}
