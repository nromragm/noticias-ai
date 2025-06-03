<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use App\Models\Comentario;
use Illuminate\Support\Facades\Auth;

class ComentarioItem extends Component
{
    public $comentario;

    protected $listeners = ['comentarioEliminado' => 'render'];

    public function mount()
    {
        $this->comentario->loadMissing('user');
    }

    // Validar que el usuario tiene permiso para eliminar el comentario y si es así, eliminarlo
    public function eliminar()
    {
        if (
            $this->comentario->user_id === Auth::id() ||
            (Auth::check() && Auth::user()->isAdmin())
        ) {
            $this->comentario->delete();

            // Emitir evento para que el componente refresque la lista
            $this->dispatch('comentarioEliminado');
        }
    }

    public function render()
    {
        return view('livewire.comentarios.comentario-item');
    }
}