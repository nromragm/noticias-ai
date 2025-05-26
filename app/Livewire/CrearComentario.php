<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Comentarios;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Noticias;

class CrearComentario extends Component
{
    public Noticias $noticia;
    public $comentario;

    protected $rules = [
        'comentario' => 'required|string|min:3'
    ];

    public function comentar()
    {
        $this->validate();

        Comentarios::create([
            'noticia_id' => $this->noticia->id,
            'user_id' => Auth::id(),
            'comentario' => $this->comentario,
        ]);

        $this->reset('comentario');
        $this->dispatch('comentarioAgregado');

    }

    public function render()
    {
        return view('livewire.crear-comentario');
    }
}