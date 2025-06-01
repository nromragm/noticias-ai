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

    public function eliminar()
    {
        if ($this->comentario->user_id === Auth::id()) {
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