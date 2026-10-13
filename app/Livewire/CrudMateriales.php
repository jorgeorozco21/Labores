<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Livewire\Traits\HasBorradoMasivo;
use App\Exports\ArchivoMaterialesExport;
use App\Exports\InformacionMaterialesExport;
use App\Models\Material;
use App\Imports\FilasImport;
use Illuminate\Support\Facades\Validator;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Request;

class CrudMateriales extends Component
{
    use HasBorradoMasivo, WithFileUploads;

    // Globales
    public $admin;
    public $buscador = '';
    public $filtroTipoPrestamo = '';
    public $filtroTipoDevolucion = '';
    public $vistaCompleta;

    // Campos Formularios
    public $idMaterial;
    public $nombre;
    public $descripcion;
    public $tipoPrestamo = '';
    public $tipoDevolucion = '';

    // Modal formulario
    public $modalFormulario = false;
    public $esEditar = false;

    // Modal descripcion
    public $modalDescripcion = false;

    // Modal carga masiva
    public $archivo;
    public $modalCargaMasiva = false;
    public $erroresExcel = [];

    public function mount()
    {
        $this->vistaCompleta = Request::routeIs('admin.materiales');

        $this->admin = 
            DB::table('usuarios as u')
            ->select(
                'u.nombre_usuario',
                'u.email'
            )
            ->where('u.id','=',session('id_usuario'))
            ->first()
        ;
    }

    public function render()
    {
        $materiales = 
            DB::table("materiales as m")
            ->select(
                "m.id",
                "m.nombre",
                "m.descripcion",
                "m.tipo_prestamo",
                "m.tipo_devolucion"
            )
            ->where("m.id_institucion","=",session("id_institucion"))
            ->when($this->buscador, function ($query){
                $palabras = array_filter(explode(' ',trim($this->buscador)));

                $query->where(function ($queryBuscador) use ($palabras){
                    foreach ($palabras as $palabra) {
                        $queryBuscador->orWhere('m.nombre','ilike','%'.$palabra.'%');
                    }
                });
            })
            ->when($this->filtroTipoPrestamo && $this->filtroTipoPrestamo !== 'Sin Filtro', function($query) {
                $query->where('m.tipo_prestamo','=',$this->filtroTipoPrestamo);
            })
            ->when($this->filtroTipoDevolucion && $this->filtroTipoDevolucion !== 'Sin Filtro', function($query) {
                $query->where('m.tipo_devolucion','=',$this->filtroTipoDevolucion);
            })
            ->orderBy("m.tipo_prestamo","ASC")
            ->orderBy("m.tipo_devolucion","ASC")
            ->orderBy("m.nombre","ASC")
            ->orderBy("m.created_at","DESC")
            ->paginate(40)
            ->withQueryString();
        ;

        return view('livewire.crud-materiales', [
            'materiales' => $materiales
        ]);
    }

    // Reglas de validacion esta se ejecuta cuando se hace this->validate()
    protected function rules()
    {
        return [
            'nombre' => 'required|string|max:255',
            "descripcion" => "required|string|max:255",
            'tipoPrestamo' => "required|in:prestamos por unidad,prestamos por cantidad",
            'tipoDevolucion' => "required|in:retornable,consumible"
        ];
    }

    protected function messages()
    {
        return [
            'nombre.required' => 'El Nombre es obligatorio.',
            'nombre.max' => 'El Nombre no puede exceder los 255 caracteres.',
            'descripcion.required' => 'La Descripcion es obligatoria.',
            'descripcion.max' => 'La descripcion no puede exceder los 255 caracteres.',
            'tipoPrestamo.required' => 'El tipo de prestamo es obligatorio.',
            'tipoPrestamo.in' => 'El tipo de prestamo seleccionado no es válido.',
            'tipoDevolucion.required' => 'El tipo de devolucion es obligatorio.',
            'tipoDevolucion.in' => 'El tipo de devolucion seleccionado no es válido.'
        ];
    }

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

        $material = Material::findOrFail($id);

        $this->idMaterial = $id;
        $this->nombre = $material->nombre;
        $this->descripcion = $material->descripcion;
        $this->tipoPrestamo = $material->tipo_prestamo;
        $this->tipoDevolucion = $material->tipo_devolucion;

        $this->modalFormulario = true; 
    }

    public function guardar()
    {
        $this->validate();

        Material::updateOrCreate(
            [
                'id' => $this->idMaterial
            ],
            [
                'nombre' => $this->nombre,
                'descripcion' => $this->descripcion,
                'tipo_prestamo' => $this->tipoPrestamo,
                'tipo_devolucion' => $this->tipoDevolucion,
                'id_institucion' => session('id_institucion')
            ]
        );

        session()->flash('success', $this->esEditar ? 'Inventario actualizado correctamente' : 'Inventario creado correctamente');

        $this->cerrarModal();
    }

    public function limpiarCampos()
    {
        $this->idMaterial = null;
        $this->nombre = '';
        $this->descripcion = '';
        $this->tipoPrestamo = '';
        $this->tipoDevolucion = '';
        $this->resetValidation();
    }

    // Cerrar modal
    public function cerrarModal()
    {
        $this->modalFormulario = false;
        $this->limpiarCampos();
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

        // Validar que el archivo exista y sea del tipo correcto
        $this->validate([
            'archivo' => 'required|file|mimes:xlsx,xls'
        ],[
            "archivo.required" => "Tienes que subir un archivo.",
            "archivo.mimes" => "Solo se permiten archivos .xlsx, .xls"
        ]);

        $columnasEsperadas = ['nombre', 'descripcion', 'tipo_prestamo','tipo_devolucion'];

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

        // Obtenemos todas las columnas que tengan mínimo una columna llena
        $datos = $hoja->filter(function ($fila){
            return count(array_filter($fila->toArray())) >= 3;
        });

        // Comenzamos la validación de las filas
        $index = 2;
        $errores = [];
        $datosValidados = [];

        foreach ($datos as $fila){
            $info = $fila->toArray();
            
            if (isset($info['tipo_prestamo'])){
                $info['tipo_prestamo'] = strtolower(trim($info['tipo_prestamo']));
            }

            if (isset($info['tipo_devolucion'])){
                $info['tipo_devolucion'] = strtolower(trim($info['tipo_devolucion']));
            }

            $validator = Validator::make($info, [
                "nombre" => "required|string|max:255",
                "descripcion" => "required|string|max:500",
                "tipo_prestamo" => "required|string|in:prestamos por unidad,prestamos por cantidad",
                "tipo_devolucion" => "required|string|in:retornable,consumible"
            ],[
                "nombre.required" => "Fila {$index}: El Nombre es obligatorio",
                "nombre.max" => "Fila {$index}: El Nombre no puede exceder los 255 caracteres",
                "descripcion.required" => "Fila {$index}: La Descripcion es obligatoria",
                "descripcion.max" => "Fila {$index}: La Descripcion no puede exceder los 500 caracteres",
                "tipo_prestamo.required" => "Fila {$index}: El Tipo de Prestamo es obligatorio",
                "tipo_prestamo.in" => "Fila {$index}: El Tipo de Prestamo solo puede ser 'prestamos por unidad' o 'prestamos por cantidad'",
                "tipo_devolucion.required" => "Fila {$index}: El Tipo de Devolucion es obligatorio",
                "tipo_devolucion.in" => "Fila {$index}: El Tipo de Devolucion solo puede ser 'retornable' o 'consumible'"
            ]);

            if ($validator->fails()){
                $errores = array_merge($errores, $validator->errors()->all());
            }else{
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
        foreach ($datosValidados as $material){
            Material::create($material);
        }

        // Limpiar el campo del archivo y notificar éxito
        $this->reset('archivo');
        session()->flash('success', "Información agregada correctamente");

        // Opcional si usas algún método para cerrar modales o refrescar
        $this->cerrarCargaMasiva();
    }

    // Cerrar modal carga masiva
    public function cerrarCargaMasiva()
    {
        $this->modalCargaMasiva = false;
    }

    // Seleccionar todos los materiales que se van borrar 
    public function seleccionarTodo()
    {
        $this->idsSeleccionados = 
            DB::table("materiales as m")
            ->where("m.id_institucion","=",session("id_institucion"))
            ->when($this->buscador, function ($query){
                $palabras = array_filter(explode(' ',trim($this->buscador)));

                $query->where(function ($queryBuscador) use ($palabras){
                    foreach ($palabras as $palabra) {
                        $queryBuscador->orWhere('m.nombre','ilike','%'.$palabra.'%');
                    }
                });
            })
            ->when($this->filtro && $this->filtro !== 'Sin Filtro', function($query) {
                $query->where('m.tipo','=',$this->filtro);
            })
            ->pluck('m.id')
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

        Material::whereIn('id', $this->idsSeleccionados)->delete();

        $this->reset('idsSeleccionados', 'opcionesBorrado');

        session()->flash('success', 'Materiales seleccionados eliminados correctamente.');
    }

    // Modal para mostrar la descripcion
    public function obtenerDescripcion($id)
    {
        $this->descripcion = 
            DB::table("materiales as m")
            ->select(
                "m.descripcion"
            )
            ->where("m.id","=",$id)
            ->first()
        ;

        $this->modalDescripcion = true;
    }

    public function cerrarDescripcion()
    {
        $this->modalDescripcion = false;
        $this->descripcion = '';
    }

    // Eliminar un material
    public function eliminar($id)
    {
        Material::findOrFail($id)->delete();

        session()->flash('success', 'Material eliminado correctamente.');
    }

    // Exportar informacion materiales
    public function exportarMateriales()
    {
        return Excel::download(new InformacionMaterialesExport(session('id_institucion')), 'informacion_materiales.xlsx');
    }

    // Descarga archivo de carga masiva
    public function archivoCarga(){

        return Excel::download(new ArchivoMaterialesExport, 'materiales.xlsx');
    } 
}
