<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Imports\FilasImport;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\InformacionLaboratoriosExport;
use App\Exports\ArchivoLaboratoriosExport;
use App\Models\Laboratorio;

use App\Livewire\Traits\HasBorradoMasivo;

class CrudLaboratorios extends Component
{
    use WithFileUploads, HasBorradoMasivo;
    
    // Globales
    public $admin;
    public $buscador = '';
    public $filtro = 'Sin Filtro';

    // Campos formulario
    public $idLaboratorio;
    public $nombreLaboratorio;
    public $tipoLaboratorio;
    public $cantidadComputadoras;
    
    // Modal formulario
    public $modalFormulario = false;
    public $esEditar = false;

    // Modal carga masiva
    public $archivo;
    public $modalCargaMasiva = false;
    public $erroresExcel = [];

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
    }

    public function render()
    {
        $laboratorios = 
            DB::table("laboratorios as l")
            ->select(
                "l.id",
                "l.nombre",
                "l.tipo",
                DB::raw('(SELECT COUNT(*) FROM computadoras 
                WHERE id_laboratorio = l.id 
                AND estado = \'activo\') as cantidad_computadoras')
            )
            ->where("l.id_institucion","=",session("id_institucion"))
            ->when($this->buscador, function($query) {
                $query->where(function($q) {
                    $q->where('l.nombre','ilike','%'.$this->buscador.'%');
                });
            })
            ->when($this->filtro && $this->filtro !== 'Sin Filtro', function($query) {
                $query->where('l.tipo','=',$this->filtro);
            })
            ->orderBy('l.tipo', 'asc')
            ->orderBy('l.nombre', 'asc')
            ->paginate(40)
            ->withQueryString();
        ;
        return view('livewire.crud-laboratorios', [
            'laboratorios' => $laboratorios
        ]);
    }

    // Reglas de validacion esta se ejecuta cuando se hace this->validate()
    protected function rules()
    {
        return [
            'nombreLaboratorio' => 'required|string|max:255',
            'tipoLaboratorio' => 'required|in:prestamos,computo,mixto',
            'cantidadComputadoras' => [
                Rule::requiredIf(in_array($this->tipoLaboratorio, ['computo', 'mixto'])),
                'nullable',
                'integer',
                'min:1'
            ],
        ];
    }

    protected function messages()
    {
        return [
            'nombreLaboratorio.required' => 'El Nombre es obligatorio',
            'nombreLaboratorio.max' => 'El Nombre no puede exceder los 255 caracteres',
            'tipoLaboratorio.required' => 'El tipo de laboratorio es obligatorio',
            'cantidadComputadoras.required' => 'La cantidad de computadoras es obligatoria para este tipo de laboratorio',
            'cantidadComputadoras.min' => 'La Cantidad minima permitida es de 1',
        ];
    }

    // Abrir modal de crear
    public function crear()
    {
        $this->esEditar = false;
        $this->limpiarCampos();
        $this->modalFormulario = true;
    }

    // Abrir modal de editar
    public function editar($id)
    {
        $this->esEditar = true;
        $this->limpiarCampos();

        $laboratorio = Laboratorio::findOrFail($id);

        $this->idLaboratorio = $laboratorio->id;
        $this->nombreLaboratorio = $laboratorio->nombre;
        $this->tipoLaboratorio = $laboratorio->tipo;
        $this->cantidadComputadoras = $laboratorio->cantidad_computadoras;

        $this->modalFormulario = true; 
    }

    // Sirve para crear o editar la informacion
    public function guardar()
    {
        $this->validate();

        $esNuevo = empty($this->idLaboratorio);

        $laboratorio = Laboratorio::updateOrCreate(
            ['id' => $this->idLaboratorio],
            [
                'nombre' => $this->nombreLaboratorio,
                'tipo' => $this->tipoLaboratorio,
                'cantidad_computadoras' => $this->tipoLaboratorio == "prestamos" ? null : $this->cantidadComputadoras,
                'id_institucion' => session('id_institucion')
            ]
        );

        if ($esNuevo && ($this->tipoLaboratorio == "computo" || $this->tipoLaboratorio == "mixto")){
        
            $computadorasParaInsertar = [];

            for ($i=1;$i<=$this->cantidadComputadoras;$i++){
                $computadorasParaInsertar[] = [
                    'numero_computadora' => (string) $i,
                    'estado' => 'activo',
                    'id_laboratorio' => $laboratorio->id,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }

            if (!empty($computadorasParaInsertar)) {
                DB::table('computadoras')->insert($computadorasParaInsertar);
            }
        }

        session()->flash('success', $esNuevo ? 'Laboratorio creado correctamente' : 'Laboratorio actualizado correctamente');

        $this->cerrarModal();
    }

    // Eliminar laboratorio
    public function eliminar($id)
    {
        Laboratorio::findOrFail($id)->delete();

        session()->flash('success', 'Laboratorio eliminado correctamente.');
    }
    
    // Limpiar inputs
    public function limpiarCampos()
    {
        $this->idLaboratorio = null;
        $this->nombreLaboratorio = '';
        $this->tipoLaboratorio = 'prestamos';
        $this->cantidadComputadoras = 1;
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

        // 1. Validar que el archivo exista y sea del tipo correcto
        $this->validate([
            'archivo' => 'required|file|mimes:xlsx,xls'
        ],[
            "archivo.required" => "Tienes que subir un archivo",
            "archivo.mimes" => "Solo se permiten archivos .xlsx, .xls"
        ]);

        $columnasEsperadas = ['nombre', 'tipo', 'cantidad_computadoras'];

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
            return strtolower($item);
        })->toArray();

        // Validar columnas faltantes
        $faltantes = array_diff($columnasEsperadas, $columnas);

        if (count($faltantes) > 0){
            $this->erroresExcel = ["Estructura inválida. Columnas faltantes: " . implode(', ', $faltantes)];
            return;
        }

        // Filtrar filas que tengan al menos una columna llena
        $datos = $hoja->filter(function ($fila){
            return count(array_filter($fila->toArray())) >= 2;
        });

        $errores = [];
        $index = 2;
        $datosValidados = [];
        
        foreach ($datos as $fila){
            $info = $fila->toArray();
            if (isset($info['tipo'])){
                $info['tipo'] = strtolower(trim($info['tipo']));
            }

            if (isset($info['cantidad_computadoras']) && trim($info['cantidad_computadoras']) === ''){
                $info['cantidad_computadoras'] = null;
            }

            $validator = Validator::make($info, [
                "nombre" => "required|string|max:255",
                "tipo" => "required|string|in:prestamos,computo,mixto"
            ],[
                "nombre.required" => "Fila {$index}: El Nombre es obligatorio",
                "nombre.max" => "Fila {$index}: El Nombre no puede exceder los 255 caracteres",
                "tipo.required" => "Fila {$index}: El Tipo de Laboratorio es obligatorio",
                "tipo.in" => "Fila {$index}: El Tipo solo puede ser 'prestamos' o 'computo' o 'mixto'"
            ]);

            $validator->after(function ($validator) use ($info, $index){
                if ($info['tipo'] == "prestamos" && $info['cantidad_computadoras'] != null){
                    $validator->errors()->add('cantidad_computadoras', "Fila {$index}: Un Laboratorio de tipo de Préstamos no puede tener computadoras.");
                }

                if ($info['tipo'] == "computo" || $info['tipo'] == "mixto"){
                    if (is_null($info['cantidad_computadoras'])){
                        $validator->errors()->add('cantidad_computadoras', "Fila {$index}: Si el laboratorio es de tipo 'computo' o 'mixto', debes asignar una cantidad de computadoras.");
                    } elseif (!is_numeric($info['cantidad_computadoras']) || (int)$info['cantidad_computadoras'] <= 0) {
                        $validator->errors()->add('cantidad_computadoras', "Fila {$index}: La cantidad de computadoras debe ser un número entero mayor a 0.");
                    }
                }
            });

            if ($validator->fails()){
                $errores = array_merge($errores, $validator->errors()->all());
            }else{
                $info['cantidad_computadoras'] = is_null($info['cantidad_computadoras']) ? null : (int)$info['cantidad_computadoras'];
                $info['id_institucion'] = session('id_institucion');
                $datosValidados[] = $info;
            }

            $index++;
        }

        // Si hay errores de validación en las filas, los devolvemos a la vista de Livewire
        if (count($errores)){
            // Puedes usar session flash o errores personalizados en Livewire
            $this->erroresExcel = $errores;
            return;
        }

        // Inserción de laboratorios y computadoras
        foreach ($datosValidados as $laboratorio){
            $nuevoLaboratorio = Laboratorio::create($laboratorio);

            if ($nuevoLaboratorio->tipo == 'computo' || $nuevoLaboratorio->tipo == 'mixto'){
                $computadorasParaInsertar = [];

                for ($i=1;$i<=$nuevoLaboratorio->cantidad_computadoras;$i++){
                    $computadorasParaInsertar[] = [
                        'numero_computadora' => "$i",
                        'estado' => 'activo',
                        'id_laboratorio' => $nuevoLaboratorio->id, 
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
                
                DB::table('computadoras')->insert($computadorasParaInsertar);
            }
        }

        // Limpiar el campo del archivo y notificar éxito
        $this->reset('archivo');
        session()->flash('success', "Información agregada correctamente");
        
        // Opcional: Emitir un evento o cerrar el modal si usas Alpine/Livewire integrado
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
            DB::table("laboratorios as l")
            ->where("l.id_institucion", "=", session("id_institucion"))
            ->when($this->buscador, function($query) {
                $query->where('l.nombre', 'ilike', '%' . $this->buscador . '%');
            })
            ->when($this->filtro && $this->filtro !== 'Sin Filtro', function($query) {
                $query->where('l.tipo', '=', $this->filtro);
            })
            ->pluck('l.id')
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

        Laboratorio::whereIn('id', $this->idsSeleccionados)->delete();

        $this->reset('idsSeleccionados', 'opcionesBorrado');

        session()->flash('success', 'Laboratorios seleccionados eliminados correctamente.');
    }

    // Exportar Informacion laboratorios
    public function exportarLaboratorios()
    {
        return Excel::download(new InformacionLaboratoriosExport(session('id_institucion')), 'informacion_laboratorios.xlsx');
    }

    // Descarga archivo de carga masiva
    public function archivoCarga(){
        return Excel::download(new ArchivoLaboratoriosExport, 'laboratorios.xlsx');
    }
}
