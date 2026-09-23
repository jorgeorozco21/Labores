<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\HistorialAlumno;
use App\Models\Solicitud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuditoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        $info = $request->except('_token','_method');

        if ($info['estado'] == 'recibido'){
            $solicitud = Solicitud::findOrFail($info['id_solicitud']);

            $materiales = $solicitud->info_material;

            if (count($materiales) == count($info['seleccionados'])){
                foreach ($materiales as $m){
                    DB::table('inventarios')
                        ->where('id', $m['id'])
                        ->increment('cantidad_disponible', $m['cantidad']);
                }

                $info = [
                    'id_solicitud' => $info['id_solicitud'],
                    'estado' => $info['estado'],
                    'info_usuario' => $info['info_auditoria']
                ];
            }else{
                $noSeleccionados = [];
                $seleccionados = [];
                foreach ($materiales as $m){
                    if (in_array($m['id'], $info['seleccionados'])){
                        DB::table('inventarios')
                            ->where('id', $m['id'])
                            ->increment('cantidad_disponible', $m['cantidad']);
                        $seleccionados[] = $m;
                    }else{
                        $noSeleccionados[] = $m;
                    }
                }

                Solicitud::where('id','=',$info['id_solicitud'])->update(['info_material' => $seleccionados]);

                HistorialAlumno::create([
                    'id_solicitud' => $info['id_solicitud'],
                    'info_usuario' => $info['info_usuario'],
                    'info_auditoria' => $info['info_auditoria'],
                    'info_material' => $noSeleccionados,
                    'estado' => 'pendiente',
                    'descripcion' => $info['notas']
                ]);

                $info = [
                    'id_solicitud' => $info['id_solicitud'],
                    'estado' => $info['estado'],
                    'info_usuario' => $info['info_auditoria']
                ];
            }
        }
        
        Auditoria::create($info);

        return response()->json('Todo bien');
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
