<?php

namespace App\Http\Controllers;

use App\Models\Inventario;
use App\Models\Solicitud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\InformacionSolicitudesExport;

class SolicitudesController extends Controller
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
        $infoSolicitud =  $request->except('_token','_method');

        foreach ($infoSolicitud['info_material'] as $material){
            $cant = Inventario::find($material['id']);

            if ($material['cantidad'] > $cant->cantidad_disponible){
                return response()->json([
                    'error' => "Stock insuficiente para: {$material['nombre']}. disponible: {$cant->cantidad_disponible}"
                ], 422);
            }
        }

        foreach ($infoSolicitud['info_material'] as $material){
            $cant = Inventario::find($material['id']);

            $cant->cantidad_disponible -= $material['cantidad'];

            $cant->save();
        }

        Solicitud::create($infoSolicitud);

        $solicitudes = 
            DB::table('solicitudes as s')
            ->leftJoin('auditoria as a', function($join) {
                $join->on('s.id', '=', 'a.id_solicitud')
                    ->whereRaw('a.id = (SELECT MAX(id) FROM auditoria WHERE id_solicitud = s.id)');
            })
            ->select(
                's.id',
            )
            ->where(function($query) {
                $query->where('a.estado', '!=', 'recibido')
                    ->orWhereNull('a.estado');  
            })
            ->where('s.info_usuario->idLaboratorio','=',$infoSolicitud['info_usuario']['idLaboratorio'])
            ->where('s.info_usuario->id','=',session('id_usuario'))
            ->get()
        ;

        return response()->json($solicitudes->count());
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
        $solicitud = Solicitud::findOrFail($id);
        $materiales = $solicitud->info_material;

        foreach ($materiales as $material){
            $cant = Inventario::find($material['id']);

            $cant->cantidad_disponible += $material['cantidad'];

            $cant->save();
        }

        $solicitud->delete();
        
        return response()->json(['success' => true, 'message' => 'Eliminado correctamente']);
    }

    public function exportarSolicitudes(string $id)
    {
        $laboratorio = 
            DB::table('laboratorios as l')
            ->select(
                'l.nombre'
            )
            ->where('l.id','=',$id)
            ->first()
        ;

        $nombreLaboratorio = str_replace(' ','',$laboratorio->nombre);
        $fecha = date('Y_m_d_H_i_s');

        return Excel::download(new InformacionSolicitudesExport($id), 'informacion_solicitudes_'.$nombreLaboratorio.'_'.$fecha.'.xlsx');
    }
}
