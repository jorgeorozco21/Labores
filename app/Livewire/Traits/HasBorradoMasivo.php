<?php

namespace App\Livewire\Traits;

trait HasBorradoMasivo
{
    public $opcionesBorrado = false;
    public $idsSeleccionados = [];

    // Mostrar opciones de borrado
    public function mostrarOpcionesBorrado()
    {
        $this->opcionesBorrado = true;
    }

    // Ocultar opciones de borrado
    public function ocultarOpcionesBorrado()
    {
        $this->opcionesBorrado = false;
    }

    // Limpiar selección
    public function limpiarTodo()
    {
        $this->idsSeleccionados = [];
    }
}