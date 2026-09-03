<?php

namespace App\Http\Controllers;

use App\Models\SolicitudComputo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SolicitudesComputoController extends Controller
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
        $datos = $request->except('_token','_method','id_laboratorio');

        SolicitudComputo::create($datos);

        $infoLaboratorio = 
            DB::table('computadoras as c')
            ->leftJoin('solicitudes_computo as s', function($join) {
                $join->on('s.id_computadora', '=', 'c.id')
                    ->whereNotExists(function ($query) {
                        $query->select(DB::raw(1))
                            ->from('auditoria_computo as a')
                            ->whereColumn('a.id_solicitud', 's.id')
                            ->where('a.estado', '=', 'completado');
                    });
            })
            ->select(
                DB::raw('COUNT(s.id) as cantidad_reportes')
            )
            ->where('c.estado','=','activo')
            ->where('c.id_laboratorio', '=', $request->id_laboratorio)
            ->where('c.estado', '=', 'activo')
            ->groupBy('c.id', 'c.numero_computadora')
            ->orderBy('c.id','ASC')
            ->get()
        ;

        $totalReportes = $infoLaboratorio->sum('cantidad_reportes');

        return response()->json($totalReportes);
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
        $solicitud = SolicitudComputo::findOrFail($id);

        $solicitud->delete();

        return response()->json('Todo bien');
    }
}
