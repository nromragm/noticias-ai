<?php

namespace App\Livewire\Comentarios;

use App\Models\Noticias;
use App\Models\Comentarios;
use App\Models\User;
use Livewire\Component;

class SeccionComentarios extends Component
{
    public $noticia;

    protected $listeners = ['comentarioAgregado' => '$refresh'];

    public function mount(Noticias $noticia)
    {
        $this->noticia = $noticia;
    }

    public function render()
    {
        $comentarios = $this->noticia->comentarios()->with('user')->latest()->get();

        return view('livewire.comentarios.seccion-comentarios', compact('comentarios'));
    }
}