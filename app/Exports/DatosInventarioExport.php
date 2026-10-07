<?php

namespace App\Exports;

use App\Models\Laboratorio;
use App\Models\Material;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Protection;

class DatosInventarioExport implements FromCollection, WithEvents, WithTitle
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return collect([
            ["id_material","cantidad_total"]
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

                $cantMateriales = Material::where("id_institucion", $id_institucion)->count();

                $primerMaterial = Material::where("id_institucion", $id_institucion)->orderBy('id')->value('nombre');

                $valMat = new DataValidation();
                $valMat->setType(DataValidation::TYPE_LIST)
                    ->setErrorStyle(DataValidation::STYLE_STOP)
                    ->setAllowBlank(true)                
                    ->setShowErrorMessage(true)                 
                    ->setShowDropDown(true)
                    ->setErrorTitle('Material no válido')
                    ->setError('Selecciona un material de la lista.')
                    ->setFormula1("='Materiales'!\$B\$1:\$B\${$cantMateriales}");

                for ($fila = 2; $fila <= 1000; $fila++) {
                    $hoja->getCell("A{$fila}")->setDataValidation($valMat);

                    if ($primerMaterial){
                        $hoja->setCellValue("A{$fila}", $primerMaterial);
                    }
                }

                $hoja->getColumnDimension('A')->setWidth(40); // Ancho de 30 para la columna A
                $hoja->getColumnDimension('B')->setAutoSize(true); // Auto-ajuste para la columna B
                
                // 1. Configurar la protección
                $proteccion = $hoja->getProtection();
                $proteccion->setSheet(true); 

                // 3. Desbloquear el rango de entrada de datos (A2:B1000)
                $hoja->getStyle('A2:B1000')->getProtection()->setLocked(Protection::PROTECTION_UNPROTECTED);
            }
        ];
    }
}
