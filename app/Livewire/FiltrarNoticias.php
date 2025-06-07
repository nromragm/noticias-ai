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

    // Método que se ejecuta al montar el componente
    public function mount()
    {
        $this->categorias = Noticias::CATEGORIAS;
    }

    // Aumenta el número de noticias a mostrar al hacer clic en "Ver más"
    public function loadMore()
    {
        $this->porPagina += 9;
    }

    public function buscar()
    {

        $this->porPagina = 9;
    }

    // Renderiza la vista del componente
    public function render()
    {
        // Crea una consulta base
        $query = Noticias::query();

        // Aplica filtro por categoría si se ha seleccionado una
        if ($this->categoria) {
            $query->where('categoria', $this->categoria);
        }

        // Aplica filtro de búsqueda si hay texto
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('titulo', 'like', '%' . $this->search . '%')
                ->orWhere('descripcion', 'like', '%' . $this->search . '%')
                ->orWhere('contenido', 'like', '%' . $this->search . '%');
            });
        }
        
        // Cuenta el total de resultados con los filtros actuales
        $this->total = $query->count();

        // Aplica el orden y limita la cantidad de resultados
        $noticias = $query->orderBy('published_at', $this->orden)
                          ->take($this->porPagina)
                          ->get();

        return view('livewire.noticias.filtrar-noticias', [
            'noticias' => $noticias,
            'total' => $this->total,
        ])->layout('layouts.app', [
            'title' => 'Noticias',
            'description' => 'Filtrar noticias por categoría o búsqueda',
        ]);
    }
}
