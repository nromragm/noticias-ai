<?php

namespace App\Livewire;

use App\Models\Noticias;
use App\Models\Comentarios;
use App\Models\User;
use Livewire\Component;

/**
 * Componente Livewire para mostrar una noticia específica y sus comentarios.
 */
class NoticiaShow extends Component
{
    public Noticias $noticia;

    public function mount(Noticias $noticia)
    {
        $this->noticia = $noticia;
    }

    
    public function render()
    {
        return view('livewire.noticias.noticia-show', [
            'comentarios' => $this->noticia->comentarios()->with('user')->latest()->get(),
            'noticia' => $this->noticia,
            'relacionadas' => $this->noticia->relacionadas(),
        ])->layout('layouts.app2');
    }
}