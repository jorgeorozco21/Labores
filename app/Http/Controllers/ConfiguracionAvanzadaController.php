<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConfiguracionAvanzadaController extends Controller
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
        //
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
        $request['limite_solicitudes_prestamos'] = $request->limite_solicitudes_prestamos === 'Sin limite' ? -1 : $request->limite_solicitudes_prestamos;
        $request['limite_solicitudes_computo'] = $request->limite_solicitudes_computo === 'Sin limite' ? -1 : $request->limite_solicitudes_computo;
        $request['limite_bloqueo'] = $request->limite_bloqueo === 'Sin limite' ? -1 : $request->limite_bloqueo;

        $request->validate([
            'limite_solicitudes_prestamos' => 'required|integer|min:-1',
            'limite_solicitudes_computo' => 'required|integer|min:-1',
            'limite_bloqueo' => 'required|integer|min:-1',
        ]);

        $informacion = $request->except('_token', '_method');
        $informacion['id_institucion'] = session('id_institucion');

        DB::table('configuracion_avanzada')->where('id', $id)->update($informacion);

        return redirect()->route('admin.configuracionAvanzada.index')->with('success', 'Configuración avanzada actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
