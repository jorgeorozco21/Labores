<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\Grupo;
use App\Models\GrupoLaboratorio;
use App\Models\Laboratorio;
use App\Exports\InformacionGruposExport;
use Maatwebsite\Excel\Facades\Excel;

use App\Livewire\Traits\HasBorradoMasivo;

class CrudGrupos extends Component
{
    use HasBorradoMasivo;

    // Globales
    public $admin;
    public $laboratorios;
    public $buscador = '';
    public $filtro = '';
    public $laboratoriosSeleccionados = [];
    
    // Modal formulario
    public $modalFormulario = false;
    public $esEditar = false;

    // Modal laboratorios
    public $modalLaboratorios = false;
    public $laboratoriosMostrar;
    
    // Campos formulario
    public $idGrupo;
    public $nombre;
    public $tipoGrupo;
    public $selectLaboratorio = ''; 

    public function mount()
    {
        $this->admin = 
            DB::table('usuarios as u')
            ->select(
                'u.nombre_usuario',
                'u.email'
            )
            ->where('u.id','=',session('id_usuario'))
            ->first()
        ;

        $this->laboratorios = 
            DB::table("laboratorios as l")
            ->select(
                "l.id",
                "l.nombre"
            )
            ->where("l.id_institucion","=",session("id_institucion"))
            ->orderBy('l.tipo', 'asc')
            ->orderBy('l.nombre', 'asc')
            ->get()
        ;
    }

    public function render()
    {
        $grupos = 
            DB::table("grupos as g")
            ->select(
                "g.id",
                "g.nombre",
                "g.tipo_grupo"
            )
            ->where("g.id_institucion","=",session("id_institucion"))
            ->when($this->buscador, function ($query){
                $palabras = array_filter(explode(' ',trim($this->buscador)));

                $query->where(function ($queryBuscador) use ($palabras){
                    foreach ($palabras as $palabra) {
                        $queryBuscador->orWhere('g.nombre','ilike','%'.$palabra.'%');
                    }
                });
            })
            ->when($this->filtro && $this->filtro !== 'Sin Filtro', function($query) {
                $query->where('g.tipo_grupo','=',$this->filtro);
            })
            ->orderBy('g.tipo_grupo','asc')
            ->orderBy('g.nombre','asc')
            ->paginate(40)
            ->withQueryString();
        ;

        return view('livewire.crud-grupos', [
            'grupos' => $grupos
        ]);
    }

    // Reglas de validacion esta se ejecuta cuando se hace this->validate()
    protected function rules()
    {
        return [
            'nombre' => 'required|string|max:255',
            'tipoGrupo' => 'required|in:principal,secundario',
            'laboratoriosSeleccionados' => 'required|array|min:1'
        ];
    }

    protected function messages()
    {
        return [
            "nombre.required" => "El Nombre es obligatorio.",
            "nombre.string" => "El Nombre debe ser un texto válido.",
            "nombre.max" => "El Nombre no puede exceder los 255 caracteres.",
            "tipoGrupo.required" => "El Tipo de Grupo es obligatorio.",
            "tipoGrupo.in" => "El Tipo de Grupo seleccionado no es válido.",
            "laboratoriosSeleccionados.required" => "Debes seleccionar al menos un laboratorio.",
            "laboratoriosSeleccionados.min" => "Debes seleccionar al menos un laboratorio.",
        ];
    }

    // Mostrar Laboratorios
    public function mostrarLaboratorios($id)
    {
        $this->laboratoriosMostrar =
            DB::table("grupo_laboratorios as lg")
            ->join('laboratorios as l','l.id','=','lg.id_laboratorio')
            ->select(
                'lg.id_laboratorio',
                'l.nombre'
            )
            ->where('lg.id_grupo','=',$id)
            ->get()
        ;

        $this->modalLaboratorios = true;
    }

    public function cerrarModalLaboratorios()
    {
        $this->modalLaboratorios = false;

        $this->laboratoriosMostrar = [];
    }

    // Abrir modal de crear
    public function crear()
    {
        $this->esEditar = false;
        $this->limpiarCampos();
        $this->modalFormulario = true;
    }

    public function editar($id)
    {
        $this->esEditar = true;
        $this->limpiarCampos();

        $grupo = Grupo::findOrFail($id);

        $this->idGrupo = $id;
        $this->nombre = $grupo->nombre;
        $this->tipoGrupo = $grupo->tipo_grupo;
        
        $grupoLaboratorios =
            DB::table("grupo_laboratorios as lg")
            ->join('laboratorios as l','l.id','=','lg.id_laboratorio')
            ->select(
                'lg.id_laboratorio',
                'l.nombre'
            )
            ->where('lg.id_grupo','=',$this->idGrupo)
            ->get()
        ;

        foreach ($grupoLaboratorios as $laboratorio){
            $this->laboratoriosSeleccionados[] = ['id' => $laboratorio->id_laboratorio, 'nombre' => $laboratorio->nombre];
        }

        $this->modalFormulario = true; 
    }

    public function guardar()
    {
        $this->validate();

        $esNuevo = empty($this->idGrupo);

        $grupo = Grupo::updateOrCreate(
            [
                'id' => $this->idGrupo
            ],
            [
                'nombre' => $this->nombre,
                'tipo_grupo' => $this->tipoGrupo,
                'id_institucion' => session('id_institucion')
            ]
        );

        $idsSeleccionados = array_column($this->laboratoriosSeleccionados, 'id');

        if (!$esNuevo){
            $laboratoriosBD = GrupoLaboratorio::where('id_grupo', $grupo->id)->pluck('id_laboratorio')->toArray();

            foreach ($laboratoriosBD as $idLabBD) {
                $indice = array_search($idLabBD, $idsSeleccionados);

                if ($indice !== false) {
                    unset($idsSeleccionados[$indice]);
                } else {
                    GrupoLaboratorio::where('id_grupo', $grupo->id)->where('id_laboratorio', $idLabBD)->delete();
                }
            }
        }

        foreach ($idsSeleccionados as $laboratorio){
            GrupoLaboratorio::create([
                'id_grupo' => $grupo->id,
                'id_laboratorio' => $laboratorio
            ]);
        }

        session()->flash('success', $esNuevo ? 'Grupo creado correctamente' : 'Grupo actualizado correctamente');

        $this->cerrarModal();
    }

    public function agregarLaboratorio()
    {
        if ($this->selectLaboratorio == '') return;

        $band = true;
        foreach ($this->laboratoriosSeleccionados as $indice => $laboratorio){
            if ($laboratorio['id'] == $this->selectLaboratorio){
                $band = false;
                break;
            }
        }
        
        if ($band){
            $laboratorio = Laboratorio::where('id',$this->selectLaboratorio)->first();

            if ($laboratorio){
                $this->laboratoriosSeleccionados[] = ['nombre' => $laboratorio->nombre, 'id' => $laboratorio->id];
            }
        }
    }

    public function eliminarLaboratorio($id)
    {
        foreach ($this->laboratoriosSeleccionados as $indice => $laboratorio){
            if ($laboratorio['id'] == $id){
                unset($this->laboratoriosSeleccionados[$indice]);
                break;
            }
        }

        $this->laboratoriosSeleccionados = array_values($this->laboratoriosSeleccionados);
    }

    // Limpiar campos de formulario
    public function limpiarCampos()
    {
        $this->nombre = '';
        $this->tipoGrupo = '';
        $this->selectLaboratorio = '';
        $this->laboratoriosSeleccionados = [];
    }

    // Cerrar modal
    public function cerrarModal()
    {
        $this->modalFormulario = false;
        $this->limpiarCampos();
    }

    // Eliminar Grupo
    public function eliminar($id)
    {
        Grupo::findOrFail($id)->delete();

        session()->flash('success', 'Grupo eliminado correctamente.');
    }

    // Selecciona todos los ids
    public function seleccionarTodo()
    {
        $this->idsSeleccionados = 
            DB::table("grupos as g")
            ->select(
                "g.id",
                "g.nombre",
                "g.tipo_grupo"
            )
            ->where("g.id_institucion","=",session("id_institucion"))
            ->when($this->buscador, function ($query){
                $palabras = array_filter(explode(' ',trim($this->buscador)));

                $query->where(function ($queryBuscador) use ($palabras){
                    foreach ($palabras as $palabra) {
                        $queryBuscador->orWhere('g.nombre','ilike','%'.$palabra.'%');
                    }
                });
            })
            ->when($this->filtro && $this->filtro !== 'Sin Filtro', function($query) {
                $query->where('g.tipo_grupo','=',$this->filtro);
            })
            ->pluck('g.id')
            ->map(fn($id) => (string) $id) 
            ->toArray()
        ;
    }

    // Borrar elementos seleccionados
    public function borrarSeleccionados()
    {
        if (empty($this->idsSeleccionados)){
            return;
        }

        Grupo::whereIn('id', $this->idsSeleccionados)->delete();

        $this->reset('idsSeleccionados', 'opcionesBorrado');

        session()->flash('success', 'Grupos seleccionados eliminados correctamente.');
    }

    // Exportar grupos
    public function exportarGrupos()
    {
        return Excel::download(new InformacionGruposExport(session('id_institucion')), 'informacion_grupos.xlsx');
    }
}
