<?php

namespace App\Http\Controllers;

use App\Models\HistorialAlumno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HistorialAlumnosController extends Controller
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

        $historial = HistorialAlumno::where('id','=',$info['id_solicitud'])->first();

        $infoHistorial = [
            'id_solicitud' => $historial->id_solicitud,
            'info_usuario' => $historial->info_usuario,
            'info_auditoria' => $info['info_auditoria'],
            'info_material' => $historial->info_material,
            'estado' => 'recibido',
            'descripcion' => $historial->descripcion
        ];
        
        foreach ($historial->info_material as $m){
            DB::table('inventarios')
            ->where('id', $m['id'])
            ->increment('cantidad_disponible', $m['cantidad']);
        }
        
        HistorialAlumno::create($infoHistorial);

        return response()->json('todo bien');
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
