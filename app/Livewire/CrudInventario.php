<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\InformacionInventarioExport;
use App\Exports\ArchivoInventarioExport;
use App\Models\Inventario;
use Livewire\WithFileUploads;
use App\Imports\FilasImport;
use Illuminate\Support\Facades\Validator;

use App\Livewire\Traits\HasBorradoMasivo;

class CrudInventario extends Component
{
    use WithFileUploads, HasBorradoMasivo;

    // Globales
    public $id = null;
    public $admin;
    public $materiales;
    public $laboratorio = null;
    public $laboratorios;
    public $buscador;
    public $filtro;

     // Campos formulario
    public $idInventario;
    public $idMaterial;
    public $cantidadTotal;

    // Modal formulario
    public $modalFormulario = false;
    public $esEditar = false;

    // Modal carga masiva
    public $archivo;
    public $modalCargaMasiva = false;
    public $erroresExcel = [];

    public function mount($id = null)
    {
        $this->id = $id;

        $this->admin = 
            DB::table('usuarios as u')
            ->select(
                'u.nombre_usuario',
                'u.email'
            )
            ->where('u.id','=',session('id_usuario'))
            ->first()
        ;
        
        $this->materiales = 
            DB::table("materiales as m")
            ->select(
                "m.id",
                "m.nombre"
            )
            ->where("m.id_institucion","=",session("id_institucion"))
            ->orderBy("m.nombre","ASC")
            ->orderBy("m.created_at","DESC")
            ->get()
        ;

        $this->laboratorios =
            DB::table("laboratorios as l")
            ->select(
                "l.id",
                "l.nombre"
            )
            ->whereIn("l.tipo", ["prestamos", "mixto"])
            ->where("l.id_institucion","=",session("id_institucion"))
            ->orderBy("l.nombre","ASC")
            ->orderBy("l.created_at","DESC")
            ->get()
        ;

        if (!empty($id)){
            $this->laboratorio = 
                DB::table('laboratorios as l')
                ->select(
                    'l.id',
                    'l.nombre',
                    'l.tipo'
                )
                ->where('l.id','=',$this->id)
                ->first()
            ;
        }
    }

    public function render()
    {
        $inventarios = 
            DB::table("inventarios as i")
            ->join("materiales as m","m.id","=","i.id_material")
            ->join("laboratorios as l","l.id","=","i.id_laboratorio")
            ->select(
                "i.id",
                "m.nombre as nombreMaterial",
                "i.cantidad_total",
                'i.cantidad_disponible',
                "l.nombre as nombreLaboratorio"
            )
            ->where("l.id_institucion","=",session("id_institucion"))
            ->when($this->id, function ($query) {
                $query->where('i.id_laboratorio', '=', $this->id); 
            })
            ->when($this->buscador, function ($query){
                $palabras = array_filter(explode(' ',trim($this->buscador)));

                $query->where(function ($queryBuscador) use ($palabras){
                    foreach ($palabras as $palabra) {
                        $queryBuscador->orWhere('m.nombre','ilike','%'.$palabra.'%');
                    }
                });
            })
            ->when($this->filtro && $this->filtro !== 'Sin Filtro', function($query) {
                $query->where('i.id_laboratorio','=',$this->filtro);
            })
            ->orderBy("l.nombre","ASC")
            ->orderBy("m.nombre","ASC")
            ->orderBy("i.created_at","DESC")
            ->paginate(40)
            ->withQueryString();
        ;

        return view('livewire.crud-inventario', [
            'inventarios' => $inventarios
        ]);
    }

    // Reglas de validacion esta se ejecuta cuando se hace this->validate()
    protected function rules()
    {
        return [
            'idMaterial' => 'required',
            "cantidadTotal" => "required|integer|min:1",
        ];
    }

    protected function messages()
    {
        return [
            "idMaterial.required" => "Debe seleccionar un material", 
            "cantidadTotal.required" => "La Cantidad es obligatoria",
            "cantidadTotal.min" => "La Cantidad minima es de 1",
        ];
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

        $inventario = Inventario::findOrFail($id);

        $this->idInventario = $id;
        $this->idMaterial = $inventario->id_material;
        $this->cantidadTotal = $inventario->cantidad_total;

        $this->modalFormulario = true; 
    }

    // Sirve para crear o editar la informacion
    public function guardar()
    {
        $this->validate();

        $esNuevo = empty($this->idInventario);

        $cantidadDisponible = $this->cantidadTotal;

        if (!$esNuevo){
            $inventarioActual = Inventario::findOrFail($this->idInventario);

            $cantidadDisponible = $inventarioActual->cantidad_disponible;

            if ($this->cantidadTotal >= $inventarioActual->cantidad_total){
                $cantidadDisponible = $inventarioActual->cantidad_disponible + ($this->cantidadTotal - $inventarioActual->cantidad_total);
            }else{
                if ($inventarioActual->cantidad_disponible != $inventarioActual->cantidad_total){
                    session()->flash('error', 'La cantidad total no se puede disminuir cuando es diferente a la cantidad disponible.');
                    return;
                }else{
                    $cantidadDisponible = $this->cantidadTotal;
                }
            }
        }

        Inventario::updateOrCreate(
            [
                'id' => $this->idInventario
            ],
            [
                'id_material' => $this->idMaterial,
                'cantidad_disponible' => $cantidadDisponible,
                'cantidad_total' => $this->cantidadTotal,
                'id_laboratorio' => $this->id
            ]
        );

        session()->flash('success', $esNuevo ? 'Inventario creado correctamente' : 'Inventario actualizado correctamente');

        $this->cerrarModal();
    }

    // Eliminar Inventario
    public function eliminar($id)
    {
        $inventario = Inventario::findOrFail($id);

        if ($inventario->cantidad_disponible != $inventario->cantidad_total){
            session()->flash('error', 'No se puede borrar un invetario si la cantidad total no es igual a la disponible');
        }

        $inventario->delete();

        session()->flash('success', 'Inventario eliminado correctamente.');
    }

    // Cerrar modal
    public function cerrarModal()
    {
        $this->modalFormulario = false;
        $this->limpiarCampos();
    }

    // Limpiar inputs
    public function limpiarCampos()
    {
        $this->idInventario = null;
        $this->idMaterial = null;
        $this->cantidadTotal = 1;
        $this->resetValidation();
    }

    // Abrir modal de carga masiva
    public function abrirCargaMasiva()
    {
        $this->modalCargaMasiva = true;
    }

    public function ejecutarCarga()
    {
        // Limpiamos errores anteriores al reintentar
        $this->reset('erroresExcel');

        // 1. Validar que el archivo exista y sea del tipo correcto
        $this->validate([
            'archivo' => 'required|file|mimes:xlsx,xls'
        ],[
            "archivo.required" => "Tienes que subir un archivo",
            "archivo.mimes" => "Solo se permiten archivos .xlsx, .xls"
        ]);

        $columnasEsperadas = ['id_material', 'cantidad_total'];

        // Obtener la ruta temporal del archivo subido por Livewire
        $rutaArchivo = $this->archivo->getRealPath();

        $contenido = Excel::toCollection(new FilasImport, $rutaArchivo);
        $hoja = $contenido[0];

        if ($hoja->isEmpty()){
            $this->erroresExcel = ["El Archivo Excel está vacío."];
            return;
        }

        // Convertir encabezados del usuario a minúsculas
        $columnas = $hoja->first()->keys()->map(function($item){
            return strtolower(trim($item));
        })->toArray();

        // Validamos las columnas sin importar el orden ni las mayúsculas/minúsculas
        $faltantes = array_diff($columnasEsperadas, $columnas);

        if (count($faltantes) > 0){
            $this->erroresExcel = ["Estructura inválida. Columnas faltantes: " . implode(', ', $faltantes)];
            return;
        }

        $datos = $hoja->filter(function ($fila){
            return count(array_filter($fila->toArray())) >= 2;
        });

        // Comenzamos la validación de las filas
        $index = 2;
        $errores = [];
        $datosValidados = [];

        foreach ($datos as $fila){
            $info = $fila->toArray();

            $validator = Validator::make($info, [
                "id_material" => "required|string|max:255",
                "cantidad_total" => "required|integer|min:1",
            ],[
                "id_material.required" => "Fila {$index}: El Material es obligatorio",
                "id_material.max" => "Fila {$index}: El Nombre del Material no puede exceder los 255 caracteres",
                "cantidad_total.required" => "Fila {$index}: La Cantidad es obligatoria",
                "cantidad_total.integer" => "Fila {$index}: La Cantidad tiene que ser un número",
                "cantidad_total.min" => "Fila {$index}: La Cantidad mínima es de 1",
            ]);

            if (!empty($info['id_material'])){
                $idMaterial = DB::table("materiales as m")
                    ->select("m.id")
                    ->where("m.nombre", "=", $info["id_material"])
                    ->where("m.id_institucion", "=", session("id_institucion"))
                    ->first();

                if ($idMaterial){
                    $info['id_material'] = $idMaterial->id;
                } else {
                    $validator->after(function ($validator) use ($index){
                        $validator->errors()->add('id_material', "Fila {$index}: El Material no existe.");
                    });
                }
            }

            if ($validator->fails()){
                $errores = array_merge($errores, $validator->errors()->all());
            } else {
                $info['cantidad_disponible'] = $info['cantidad_total'];
                $info['id_laboratorio'] = $this->id;
                $info['id_institucion'] = session('id_institucion');
                $datosValidados[] = $info;
            }

            $index++;
        }

        // Si hay errores en las filas, se asignan a la variable de Livewire
        if (count($errores)){
            $this->erroresExcel = $errores;
            return;
        }

        // Inserción en la base de datos
        foreach ($datosValidados as $fila){
            Inventario::create($fila);
        }

        // Limpiar el campo del archivo y notificar éxito
        $this->reset('archivo');
        session()->flash('success', "Información agregada correctamente");

        $this->cerrarCargaMasiva();
    }

     // Cerrar modal carga masiva
    public function cerrarCargaMasiva()
    {
        $this->modalCargaMasiva = false;
    }

    // Selecciona todos los ids
    public function seleccionarTodo()
    {
        $this->idsSeleccionados = 
            DB::table("inventarios as i")
            ->join("materiales as m","m.id","=","i.id_material")
            ->join("laboratorios as l","l.id","=","i.id_laboratorio")
            ->where("l.id_institucion","=",session("id_institucion"))
            ->pluck('i.id')
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

        Inventario::whereIn('id', $this->idsSeleccionados)->delete();

        $this->reset('idsSeleccionados', 'opcionesBorrado');

        session()->flash('success', 'Inventarios seleccionados eliminados correctamente.');
    }

    // Exportar informacion inventario
    public function exportarInventario()
    {
        return Excel::download(new InformacionInventarioExport('institucion',session('id_institucion')), 'informacion_inventario.xlsx');
    }

    // Exportar informacion inventario laboratorio
    public function exportarInventarioLaboratorio($id)
    {
        return Excel::download(new InformacionInventarioExport('laboratorio',$id), 'informacion_inventario.xlsx');
    }

    // Descarga archivo de carga masiva
    public function archivoCarga(){
        return Excel::download(new ArchivoInventarioExport, 'inventario.xlsx');
    }
}
