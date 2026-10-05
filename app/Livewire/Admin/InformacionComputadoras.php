<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\InformacionReportesComputoExport;
use App\Models\Computadora;
use App\Models\PlantillaComputo;
use Illuminate\Validation\ValidationException;

class InformacionComputadoras extends Component
{
    // Globales
    public $id;
    public $buscador = '';
    public $filtro = '';
    public $computadoraSeleccionada = null;
    public $errorToken = 0;

    // Reportes
    public $modalReportes = false;
    public $reportesDeComputadora = [];

    // Auditorias
    public $modalAuditorias = false;
    public $reporteSeleccionado = null;
    public $auditoriaDeReporte = [];

    // Plantilla 
    public $modalPlantilla = false;
    public $nuevaEspecificacion = '';
    public $plantillasCreadas = [];
    public $plantillasEditadas = [];

    // Especificaciones
    public $modalEspecificaciones = false;
    public $especificacionesComputadora = [];
    public $especificacionesEditadas = [];

    // Asignacion variable global
    public function mount($id)
    {
        $this->id = $id;
    }

    // Mandamos informacion que necesita la vista inicialmente y incluye el funcionamiento de los buscadores
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

        $computadoras = 
            DB::table('computadoras as c')
            ->select(
                'c.id',
                'c.numero_computadora',
                'c.estado'
            )
            ->where('c.id_laboratorio','=',$this->id)
            ->when($this->buscador, function($query) {
                $query->where(function($q) {
                    $q->where('c.numero_computadora','ilike','%'.$this->buscador.'%');
                });
            })
            ->when($this->filtro && $this->filtro !== 'Sin Filtro', function($query) {
                $query->where('c.estado','=',$this->filtro);
            })
            ->orderBy('c.id','ASC')
            ->get()
        ;

        return view('livewire.admin.informacion-computadoras', [
            'admin' => $admin,
            'laboratorio' => $laboratorio,
            'computadoras' => $computadoras
        ]);
    }

    // Modal de reportes
    public function cerrarModalReportes()
    {
        $this->modalReportes = false;
        $this->computadoraSeleccionada = null;
    }

    public function reportesComputadora($idComputadora, $numeroComputadora)
    { 
        $this->computadoraSeleccionada = $numeroComputadora;

        $this->reportesDeComputadora = 
            DB::table('solicitudes_computo as s')
            ->select(
                's.id',
                's.tipo',
                's.descripcion'
            )
            ->where('s.id_computadora','=',$idComputadora)
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('auditoria_computo as a')
                    ->whereColumn('a.id_solicitud', 's.id');
            })
            ->get()
        ;

        $this->modalReportes = true;
    }

    // Modal de Auditoria
    public function cerrarModalAuditoria()
    {
        $this->modalAuditorias = false;
        $this->reporteSeleccionado = null;
    }

    public function auditoriasReporte($idReporte)
    {
        $this->reporteSeleccionado = $idReporte;

        $this->auditoriaDeReporte =
            DB::table('auditoria_computo as a')
            ->select(
                'a.estado',
                'a.info_usuario',
                'a.created_at as fecha'
            )
            ->where('a.id_solicitud','=',$idReporte)
            ->get()
        ;

        $this->modalAuditorias = true;
    }

    // Funciones relaciondas con las creacion de plantillas
    public function plantillas()
    {
        $this->cargarEspecificaciones();

        $this->modalPlantilla = true;
    }

    public function crearPlantilla()
    {
        try{
            $this->validate([
                'nuevaEspecificacion' => 'required|min:3',
            ], [
                'nuevaEspecificacion.required' => 'El campo de la especificación es obligatorio.',
                'nuevaEspecificacion.min' => 'El nombre de la especificacion debe tener un minimo de 3 caracteres.'
            ]);
        }catch (ValidationException $e){
            $this->nuevaEspecificacion = '';
            $this->errorToken = microtime(true);
            throw $e; 
        }

        PlantillaComputo::create([
            'id_laboratorio' => $this->id,
            'nombre' => $this->nuevaEspecificacion
        ]);

        $this->nuevaEspecificacion = '';

        $this->cargarEspecificaciones();

        session()->flash('success', '¡Especificación guardada correctamente!');
    }

    public function editarPlantilla($idPlantilla)
    {
        try{
            $this->validate([
                "plantillasEditadas.{$idPlantilla}" => 'required|min:3',
            ], [
                "plantillasEditadas.{$idPlantilla}.required" => 'El campo de la especificación es obligatorio.',
                "plantillasEditadas.{$idPlantilla}.min" => 'El nombre de la especificacion debe tener un minimo de 3 caracteres.'
            ]);
        }catch (ValidationException $e){
            $this->nuevaEspecificacion = '';
            $this->errorToken = microtime(true);
            throw $e; 
        }

        $plantilla = PlantillaComputo::findOrFail($idPlantilla);

        $nuevoValor = $this->plantillasEditadas[$idPlantilla];
        $plantilla->nombre = $nuevoValor;
        $plantilla->save();

        $this->cargarEspecificaciones();

        session()->flash('success', '¡Especificación actualizada correctamente!');
    }

    public function borrarPlantilla($idPlantilla)
    {
        PlantillaComputo::findOrFail($idPlantilla)->delete();

        $this->cargarEspecificaciones();

        session()->flash('success', '¡Especificación borrar correctamente!');
    }

    // Mostrar las especificaciones ya creadas
    public function cargarEspecificaciones()
    {
        $this->plantillasCreadas = DB::table('plantillas_computo as pc')
            ->select(
                'pc.id',
                'pc.nombre'
            )
            ->where('pc.id_laboratorio','=',$this->id)
            ->orderBy('pc.id','asc')
            ->get()
        ;

        foreach ($this->plantillasCreadas as $plantilla) {
            $this->plantillasEditadas[$plantilla->id] = $plantilla->nombre;
        }
    }

    public function cerrarModalPlantilla()
    {
        $this->modalPlantilla = false;
    }

    /// Modlas de especificaciones
    public function especificaciones($idComputadora, $numeroComputadora)
    {
        $this->computadoraSeleccionada = $numeroComputadora;

        $this->especificacionesComputadora = 
            DB::table('plantillas_computo as pc')
            ->leftJoin('especificaciones_equipos_computo as eec', function($join) use ($idComputadora) {
                $join->on('pc.id','=','eec.id_plantilla')
                    ->where('eec.id_computadora', '=', $idComputadora);
            })
            ->select(
                'pc.id',
                'pc.nombre',
                'eec.descripcion'
            )
            ->where('pc.id_laboratorio', '=', $this->id)
            ->orderBy('pc.id','asc')
            ->get()
        ;

        foreach ($this->especificacionesComputadora as $especificacion) {
            $this->especificacionesEditadas[$especificacion->id] = $especificacion->descripcion ?? '';
        }

        $this->modalEspecificaciones = true;
    }

    public function asignarEspecificacion($idPlantilla)
    {
        $descripcion = $this->especificacionesEditadas[$idPlantilla] ?? '';

        DB::table('especificaciones_equipos_computo')->updateOrInsert(
            [
                'id_computadora' => $this->computadoraSeleccionada,
                'id_plantilla' => $idPlantilla
            ],
            [
                'descripcion' => $descripcion,
                'updated_at' => now(),
                'created_at' => now()
            ]
        );

        session()->flash('success', '¡Informacion asignada correctamente!');
    }

    public function cerrarModalEspecificaciones()
    {
        $this->computadoraSeleccionada = null;
        $this->modalEspecificaciones = false;
    }

    // Cambiar el estatus del equipo de computo
    public function cambiarEstado($idComputadora)
    {
        $computadora = Computadora::findOrFail($idComputadora);

        $computadora->estado = ($computadora->estado == 'activo')?'inactivo':'activo';

        $computadora->save();

        session()->flash('success', '¡El estado de la computadora se ha actualizado correctamente!');
    }

    // Cuando se remplaza el equipo de computo con sus respectivas operaciones
    public function reemplazarComputadora($idComputadora)
    {
        $computadora = Computadora::findOrFail($idComputadora);

        $computadora->estado = 'activo';

        $computadora->save();

        DB::table('solicitudes_computo as s')
        ->where('s.id_computadora','=',$idComputadora)
        ->delete();

        session()->flash('success', '¡Computadora remplazada correctamente!');
    }

    // Agregar un nuevo equipo de computo
    public function crearNuevaComputadora()
    {
        $total = DB::table('computadoras')
        ->where('id_laboratorio', $this->id)
        ->count();

        $registro = DB::table('laboratorios')->where('id', $this->id)->first();

        if ($registro) {
            DB::table('laboratorios')
                ->where('id', $this->id)
                ->update(['cantidad_computadoras' => $registro->cantidad_computadoras + 1]);
        }

        $info = [
            'numero_computadora' => $total + 1,
            'estado' => 'activo',
            'id_laboratorio' => $this->id
        ];

        Computadora::create($info);

        session()->flash('success', '¡Computadora agregada correctamente!');
    }

    // Funcion de exportacion de informacion a treves del excel
    public function exportarComputadoras()
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

        return Excel::download(new InformacionReportesComputoExport($this->id), 'informacion_computadoras_'.$nombreLaboratorio.'_'.$fecha.'.xlsx');
    }
}
