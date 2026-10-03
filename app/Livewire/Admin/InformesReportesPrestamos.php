<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\InformacionReportesMaterialesExport;

class InformesReportesPrestamos extends Component
{
    // Globales
    public $id;
    public $buscador = '';
    public $filtro = '';
    public $reporteSeleccionado = null;

    // Modal Descripcion
    public $modalDescripcion = false;
    public $descripcion;

    // Modal Auditorias
    public $modalAuditorias = false;
    public $auditorias = [];

    public function mount($id)
    {
        $this->id = $id;
    }

    public function render()
    {
        $admin = 
            DB::table('usuarios as u')
            ->select(
                'u.nombre_usuario',
                'u.email'
            )
            ->where('u.id','=',session('id_usuario'))
            ->first()
        ;

        $laboratorio = 
            DB::table('laboratorios as l')
            ->select(
                'l.id',
                'l.nombre'
            )
            ->where('l.id','=',$this->id)
            ->first()
        ;

        $materiales = 
            DB::table('inventarios as i')
            ->join('materiales as m','m.id','=','i.id_material')
            ->select(
                'i.id',
                'm.nombre'
            )
            ->where('i.id_laboratorio','=',$this->id)
            ->get()
        ;

        $reportes = 
            DB::table('reportes_materiales as r')
            ->join('inventarios as i','i.id','=','r.id_inventario')
            ->join('materiales as m','m.id','=','i.id_material')
            ->select(
                'r.id',
                'r.info_usuario',
                'r.cantidad',
                'm.nombre',
                'r.created_at as fecha'
            )
            ->where('i.id_laboratorio','=',$this->id)
            ->when($this->buscador, function ($query){
                $palabras = array_filter(explode(' ',trim($this->buscador)));

                $query->where(function ($queryBuscador) use ($palabras){
                    foreach ($palabras as $palabra) {
                        $queryBuscador->orWhere('r.info_usuario->nombre','ilike','%'.$palabra.'%');

                        $queryBuscador->orWhereRaw('CAST(r.id AS TEXT) ILIKE ?', ['%'.$palabra.'%']);
                    }
                });
            })
            ->when($this->filtro && $this->filtro != 'Sin Filtro', function ($query) {
                $query->where('r.id_inventario',[['id' => (int)$this->filtro]]);
            })
            ->orderBy('r.id','ASC')
            ->get()
        ;

        return view('livewire.admin.informes-reportes-prestamos', [
            'admin' => $admin,
            'laboratorio' => $laboratorio,
            'materiales' => $materiales,
            'reportes' => $reportes
        ]);
    }

    // Modal Descripcion
    public function abrirModalDescripcion($idReporte)
    {
        $this->reporteSeleccionado = $idReporte;

        $reporte = 
            DB::table('reportes_materiales as r')
            ->select(
                'r.descripcion'
            )
            ->where('r.id','=',$idReporte)
            ->first()
        ;

        $this->descripcion = $reporte->descripcion;
        $this->modalDescripcion = true;
    }

    public function cerrarModalDescripcion()
    {
        $this->modalDescripcion = false;
        $this->descripcion = null;
        $this->reporteSeleccionado = null;
    }

    // Modal Auditorias
    public function abrirModalAuditorias($idReporte)
    {
        $this->reporteSeleccionado = $idReporte;

        $this->auditorias =
            DB::table('auditoria_reportes_materiales as a')
            ->select(
                'a.estado',
                'a.info_usuario',
                'a.created_at as fecha'
            )
            ->where('a.id_reporte','=',$idReporte)
            ->get() 
        ;

        $this->modalAuditorias = true;
    }

    public function cerrarModalAuditorias()
    {
        $this->modalAuditorias = false;
        $this->auditorias = [];
        $this->reporteSeleccionado = null;
    }

    // Exportar reportes
    public function exportarReportes()
    {
        $laboratorio = 
            DB::table('laboratorios as l')
            ->select(
                'l.nombre'
            )
            ->where('l.id','=',$this->id)
            ->first()
        ;

        $nombreLaboratorio = str_replace(' ','',$laboratorio->nombre);
        $fecha = date('Y_m_d_H_i_s');

        return Excel::download(new InformacionReportesMaterialesExport($this->id), 'informacion_reportes_'.$nombreLaboratorio.'_'.$fecha.'.xlsx');
    }
}
