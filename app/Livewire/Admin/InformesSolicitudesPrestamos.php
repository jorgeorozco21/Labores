<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\InformacionSolicitudesExport;

class InformesSolicitudesPrestamos extends Component
{
    // Globales
    public $id;
    public $solicitudSeleccionada = null;
    public $buscador = '';
    public $filtro = '';

    // Modal auditorias
    public $modalAuditorias = false;
    public $auditorias = [];

    // Modal materiales
    public $modalMateriales = false;
    public $materialesSolicitud = [];

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
                'l.nombre',
                'l.tipo'
            )
            ->where('l.id','=',$this->id)
            ->first()
        ;

        $solicitudes = 
            DB::table('solicitudes as s')
            ->select(
                's.id',
                's.info_usuario',
                's.created_at as fecha',
            )
            ->where('s.info_usuario->idLaboratorio','=',$this->id)
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('auditoria as a')
                    ->whereColumn('a.id_solicitud', 's.id');
            })
            ->when($this->buscador, function ($query){
                $palabras = array_filter(explode(' ',trim($this->buscador)));

                $query->where(function ($queryBuscador) use ($palabras){
                    foreach ($palabras as $palabra) {
                        $queryBuscador->orWhere('s.info_usuario->nombre','ilike','%'.$palabra.'%');

                        $queryBuscador->orWhereRaw('CAST(s.id AS TEXT) ILIKE ?', ['%'.$palabra.'%']);
                    }
                });
            })
            ->when($this->filtro && $this->filtro != 'Sin Filtro', function ($query) {
                $query->whereJsonContains('info_material',[['id' => (int)$this->filtro]]);
            })
            ->orderBy('s.id','ASC')
            ->get()
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
        
        return view('livewire.admin.informes-solicitudes-prestamos', [
            'admin' => $admin,
            'laboratorio' => $laboratorio,
            'solicitudes' => $solicitudes,
            'materiales' => $materiales
        ]);
    }

    // Auditorias Reportes
    public function auditoriasReporte($idSolicitud)
    {
        $this->solicitudSeleccionada = $idSolicitud;

        $this->auditorias = 
            DB::table('auditoria as a')
            ->select(
                'a.estado',
                'a.info_usuario',
                'a.created_at as fecha'
            )
            ->where('a.id_solicitud','=',$idSolicitud)
            ->get() 
        ;

        $this->modalAuditorias = true;
    }

    public function cerrarModalAuditorias()
    {
        $this->modalAuditorias = false;
        $this->auditorias = [];
        $this->solicitudSeleccionada = null;
    }

    // Materiales Reportes
    public function materialesReporte($idSolicitud)
    {
        $this->solicitudSeleccionada = $idSolicitud;

        $this->materialesSolicitud = 
            DB::table('solicitudes as s')
            ->select(
                's.info_material'
            )
            ->where('s.id', '=', $idSolicitud)
            ->first()
        ;

        $this->modalMateriales = true;
    }

    public function cerrarModalMateriales()
    {
        $this->modalMateriales = false;
        $this->materialesSolicitud = [];
        $this->solicitudSeleccionada = null;
    }

    // Exportar Solicitudes
    public function exportarSolicitudes()
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

        return Excel::download(new InformacionSolicitudesExport($this->id), 'informacion_solicitudes_'.$nombreLaboratorio.'_'.$fecha.'.xlsx');
    }
}
