<?php

namespace App\Http\Controllers;

use App\Models\ConfiguracionAvanzada;
use App\Models\Institucion;
use App\Models\Servicios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class InstitucionesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $admin =
            DB::table("usuarios as u")
            ->select(
                "u.nombre_usuario",
                "u.email"
            )
            ->where('u.id','=',session('id_usuario'))
            ->first()
        ;

        $instituciones =
            DB::table("instituciones as i")
            ->select(
                "i.id",
                "i.nombre",
                "i.tag",
                "i.clave"
            )
            ->orderBy('i.id','asc')
            ->get()
        ;

        return view('Admin_Labores.Instituciones.index' , compact('admin', 'instituciones'));
    }

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
        $datosInstitucion = $request->except('_token','contrasena_seguridad','gestor_laboratorio');
        $datosServicios = $request->except('_token','contrasena_seguridad','nombre','clave','tag');

        $request->validate([
            'nombre' => "string|required|max:255",
            'clave' => "string|required|max:255",
            'tag' => "string|required|max:255",
        ],[
            "nombre.required" => " El Nombre es obligatorio",
            "nombre.max" => "El Nombre no puede exceder los 255 caracteres",
            "nombre.string" => "El Nombre debe de ser una cadena",
            "clave.required" => " La Clave es obligatoria",
            "clave.max" => "La Clave no puede exceder los 255 caracteres",
            "clave.string" => "La Clave debe de ser una cadena",
            "tag.required" => " El tag es obligatorio",
            "tag.max" => "El tag no puede exceder los 255 caracteres",
            "tag.string" => "El tag debe de ser una cadena",
        ]);

        if (!Hash::check($request['contrasena_seguridad'], config('app.operation_password'))){
            return redirect()->route('labores.instituciones.index')->with('error','Contraseña de validacion incorrecta');
        }

        $idServicios = Servicios::create($datosServicios);

        $datosInstitucion['id_servicio'] = $idServicios->id;

        $idInstitucion = Institucion::create($datosInstitucion);

        ConfiguracionAvanzada::create([
            'id_institucion' => $idInstitucion->id,
            'limite_solicitudes_prestamos' => '-1',
            'limite_solicitudes_computo' => '-1',
            'limite_bloqueo' => '-1'
        ]);

        return redirect()->route('labores.instituciones.index')->with('success','Informacion agregada correctamente');
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
        $datosInstitucion = $request->except('_token','contrasena_seguridad','gestor_laboratorio');
        $datosServicios = $request->except('_token','contrasena_seguridad','nombre','clave','tag');

        $request->validate([
            'nombre' => "string|required|max:255",
            'clave' => "string|required|max:255",
            'tag' => "string|required|max:255",
        ],[
            "nombre.required" => " El Nombre es obligatorio",
            "nombre.max" => "El Nombre no puede exceder los 255 caracteres",
            "nombre.string" => "El Nombre debe de ser una cadena",
            "clave.required" => " La Clave es obligatoria",
            "clave.max" => "La Clave no puede exceder los 255 caracteres",
            "clave.string" => "La Clave debe de ser una cadena",
            "tag.required" => " El tag es obligatorio",
            "tag.max" => "El tag no puede exceder los 255 caracteres",
            "tag.string" => "El tag debe de ser una cadena",
        ]);

        if (!Hash::check($request['contrasena_seguridad'], config('app.operation_password'))){
            return redirect()->route('labores.instituciones.index')->with('error','Contraseña de validacion incorrecta');
        }

        Institucion::where('id','=',$id)->update($datosInstitucion);

        $idServicios = 
            DB::table("instituciones as i")
            ->select(
                'i.id_servicio'
            )
            ->where('i.id','=',$id)
            ->first()
        ;

        Servicios::where('id','=',$idServicios->id_servicio)->update($datosServicios);

        return redirect()->route('labores.instituciones.index')->with('success','Informacion editada correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
