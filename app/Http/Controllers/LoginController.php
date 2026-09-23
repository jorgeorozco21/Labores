<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function index()
    {
        if (session()->has('id_institucion')) {
            session()->forget(['id_usuario', 'id_institucion']);
        }

        return view('Login.index');
    }

    public function login(Request $request)
    {
        $usuario = 
            DB::table("usuarios as u")
            ->select(
                "u.id",
                "u.nombre_usuario",
                "u.contrasena",
                "u.nombre",
                "u.email",
                "u.admin",
                "u.mantenimiento",
                "u.encargado",
                "u.normal",
                "u.id_institucion"
            )
            ->where("u.nombre_usuario","=",$request->nombre_usuario)
            ->first()
        ;

        if (!$usuario || !Hash::check($request->contrasena, $usuario->contrasena)) {
            return redirect()->route('login.index')->with("error", 'Usuario y/o Contraseña incorrecta')->withInput();
        }

        if ($usuario->nombre_usuario == "labores" && $usuario->email == "hola.labores.web@gmail.com" && $usuario->id_institucion == null){
            session(["tipo" => "labores"]);
            session (["id_usuario" => $usuario->id]);
            return redirect('/labores/instituciones');
        }

        $institucion = 
            DB::table("instituciones as i")
            ->join('servicios as s','s.id','=','i.id_servicio')
            ->select(
                's.gestor_laboratorio'
            )
            ->where('i.id','=',$usuario->id_institucion)
            ->first()
        ;

        if ($institucion->gestor_laboratorio == '0'){
            return redirect()->route('login.index')->with("error", 'Servicios de institucion no activos')->withInput();
        }

        $historiales = 
            DB::table('historial_alumnos as ha')
            ->whereRaw("CAST(ha.info_usuario->>'id' AS INTEGER) = ?", [$usuario->id])
            ->where('ha.estado','=','pendiente')
            ->whereNotExists(function ($query) {
                $query->from('historial_alumnos as ha_dup')
                    ->whereColumn('ha_dup.id_solicitud', 'ha.id_solicitud')
                    ->where('ha_dup.id', '!=', DB::raw('ha.id'));
            })
            ->count()
        ;

        $bloqueo =  
            DB::table('configuracion_avanzada as ca')
            ->select(
                'ca.limite_bloqueo'
            )
            ->where('ca.id_institucion','=',$usuario->id_institucion)
            ->first()
        ;

        if ($historiales >= $bloqueo->limite_bloqueo && $bloqueo->limite_bloqueo != -1){
            session(["bloqueado" => true]);
        }else{
            session(["bloqueado" => false]);
        }

        session([
            "id_usuario" => $usuario->id,
            "nombre_usuario" => $usuario->nombre_usuario,
            "nombre" => $usuario->nombre,
            "email" => $usuario->email,
            "id_institucion" => $usuario->id_institucion
        ]);

        if ($usuario->admin == "1") {
            session(["tipo" => "admin"]);
            return redirect('/admin');
        }
        
        if ($usuario->normal == "1") session(["normal" => true]);
        if ($usuario->encargado == "1") session(["encargado" => true]);
        if ($usuario->mantenimiento == "1") session(["mantenimiento" => true]);
        
        return redirect('/seleccionar-tipo-usuario');
    }

    public function logout(Request $request)
    {
        $request->session()->invalidate();      
        $request->session()->regenerateToken();

        return redirect()->route('login.index');
    }

    public function activarRol($rol)
    {

        if (!session("$rol")){
            return redirect('/seleccionar-tipo-usuario')->with('error', 'Acceso no autorizado.');
        }

        session(['tipo' => $rol]);

        return match ($rol){
            'normal' => redirect('/usuario/normal/laboratorios'),
            'encargado' => redirect('/usuario/encargado/solicitudes-pendientes'),
            'mantenimiento' => redirect('/usuario/mantenimiento/reportes-computo')
        };
    }
}
