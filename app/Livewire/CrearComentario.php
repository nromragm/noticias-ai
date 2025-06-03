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

    
    // Reglas de validación para el campo de comentario
    protected $rules = [
        'comentario' => 'required|string|min:3'
    ];


    
    public function comentar()
    {
        // Valida el contenido del comentario según las reglas definidas
        $this->validate();

        Comentarios::create([
            'noticia_id' => $this->noticia->id,
            'user_id' => Auth::id(),
            'comentario' => $this->comentario,
        ]);

        // Limpia el campo de comentario después de enviarlo
        $this->reset('comentario');
        $this->dispatch('comentarioAgregado');

    }

    public function render()
    {
        return view('livewire.comentarios.crear-comentario');
    }
}