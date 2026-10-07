<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class LaboratoriosInformes extends Component
{
    // Globales
    public $admin;
    public $buscador = '';
    public $filtro = 'Sin Filtro';

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

    // Mandamos informacion que necesita la vista inicialmente y incluye el funcionamiento de los buscadores
    public function render()
    {
        $laboratorios = 
            DB::table('laboratorios as l')
            ->select(
                'l.id',
                'l.nombre',
                'l.tipo'
            )
            ->where('l.id_institucion','=',session('id_institucion'))
            ->when($this->buscador, function($query) {
                $query->where(function($q) {
                    $q->where('l.nombre','ilike','%'.$this->buscador.'%');
                });
            })
            ->when($this->filtro && $this->filtro !== 'Sin Filtro', function($query) {
                $query->where('l.tipo','=',$this->filtro);
            })
            ->orderBy('l.tipo','asc')
            ->orderBy('l.nombre','asc')
            ->get()
        ;

        return view('livewire.laboratorios-informes', [
            'laboratorios' => $laboratorios
        ]);
    }
}
