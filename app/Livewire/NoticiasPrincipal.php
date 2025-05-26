<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Noticias;
use App\Models\Comentarios;

class NoticiasPrincipal extends Component
{

    public $noticias;

    public function mount()
    {
        // Obtener todas las noticias ordenadas por fecha
        $this->noticias = Noticias::orderBy('created_at', 'desc')->get();
    }
    
    public function render()
    {
        return view('livewire.noticias-principal');
    }
}
