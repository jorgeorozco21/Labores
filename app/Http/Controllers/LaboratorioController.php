<?php

namespace App\Http\Controllers;

use App\Exports\ArchivoLaboratoriosExport;
use App\Exports\InformacionLaboratoriosExport;
use App\Models\Computadora;
use App\Models\Grupo;
use App\Models\Laboratorio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class LaboratorioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /*public function index()
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
            ->orderBy('l.tipo', 'asc')
            ->orderBy('l.nombre', 'asc')
            ->paginate(40)
            ->withQueryString();
        ;

        return view('Admin.Laboratorios.index', compact('laboratorios','admin'));
    }*/

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $datosLaboratorio = $request->except("_token");

        $request->validate([
            "nombre" => "required|string|max:255",
            "cantidad" => "integer|min:1"
        ],[
            "nombre.required" => "El Nombre es obligatorio",
            "nombre.max" => "El Nombre no puede exceder los 255 caracteres",
            "cantidad.min" => "La Cantidad minima permitida es de 1"
        ]);

        if ($datosLaboratorio['tipo'] == "prestamos"){
            $datosLaboratorio['cantidad_computadoras'] = null;
        }

        $nuevoLaboratorio = Laboratorio::create($datosLaboratorio);

        if ($datosLaboratorio['tipo'] == "computo"){
            
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

        return redirect()->route('admin.laboratorios.index')->with('success',"Laboratorio creado correctamente");

        //return response()->json($datosLaboratorio);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $datosLaboratorio = $request->except("_token","_method");

        $request->validate([
            "nombre" => "required|string|max:255",
        ],[
            "nombre.required" => "El Nombre es obligatorio",
            "nombre.max" => "El Nombre no puede exceder los 255 caracteres",
        ]);

        $laboratorio = Laboratorio::findOrFail($id);
        $laboratorio->nombre = $request->nombre;
        $laboratorio->save();

        return redirect()->route('admin.laboratorios.index')->with('success','Informacion editada correctamente');

        //return response()->json($datosLaboratorio);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $laboratorio = Laboratorio::findOrFail($id);

        $laboratorio->delete();

        return redirect()->route('admin.laboratorios.index')->with('success',"Laboratorio borrado correctamente");
    }

    public function archivoCarga(){
        return Excel::download(new ArchivoLaboratoriosExport, 'laboratorios.xlsx');
    }

    public function exportarLaboratorios()
    {
        
        return Excel::download(new InformacionLaboratoriosExport(session('id_institucion')), 'informacion_laboratorios.xlsx');
    }
}
