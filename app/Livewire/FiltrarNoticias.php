<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Noticias;
use Illuminate\Support\Facades\Http;



class FiltrarNoticias extends Component
{
    public $categoria = null;
    public $categorias = [];
    public $porPagina = 9;
    public $total = 0;
    public $orden = 'desc';
    public $search = '';

    protected $listeners = ['searchChanged' => 'setSearch'];

    public function setSearch($value)
    {
        $this->search = $value;
        $this->porPagina = 9;
    }

    public function mount()
    {
        $this->categorias = Noticias::CATEGORIAS;
    }

    public function loadMore()
    {
        $this->porPagina += 9;
    }

    public function buscar()
    {
        // No necesitas nada si el render ya filtra por $search,
        // pero puedes reiniciar la paginación si quieres:
        $this->porPagina = 9;
    }

    public function render()
    {
        $query = Noticias::query();

        if ($this->categoria) {
            $query->where('categoria', $this->categoria);
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('titulo', 'like', '%' . $this->search . '%')
                  ->orWhere('descripcion', 'like', '%' . $this->search . '%');
            });
        }
        
        $this->total = $query->count();

        $noticias = $query->orderBy('published_at', $this->orden)
                          ->take($this->porPagina)
                          ->get();

        return view('livewire.noticias.filtrar-noticias', [
            'noticias' => $noticias,
            'total' => $this->total,
        ]);
    }
}
