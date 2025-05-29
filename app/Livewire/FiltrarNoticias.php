<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Noticias;
use Illuminate\Support\Facades\Http;



class FiltrarNoticias extends Component
{
    public $categoria = null;
    public $categorias = [];
    public $noticias = [];
    public $porPagina = 9;
    public $total = 0;
    public $orden = 'desc';


    /**
     * Inicializa las categorías y carga las noticias al montar el componente.
     */
    public function mount()
    {
        $this->categorias = Noticias::CATEGORIAS;
        $this->actualizarNoticias();
    }

    /**
     * Actualiza la categoría seleccionada y recarga las noticias.
     *
     * @param string $value
     */
    public function updatedCategoria($value)
    {
        $this->porPagina = 9;
        $this->actualizarNoticias();
    }

    public function updatedOrden($value)
    {
        $this->actualizarNoticias();
    }


    /**
     * Carga más noticias al hacer clic en el botón "Cargar más".
     * Aumenta el número de noticias por página y actualiza la lista.
     */
    public function loadMore()
    {
        $this->porPagina += 9;
        $this->actualizarNoticias();
    }


    /**
     * Actualiza la lista de noticias según la categoría seleccionada.
     * Si no hay categoría, obtiene todas las noticias.
     */
    private function actualizarNoticias()
    {
        $query = Noticias::query();
        
        if ($this->categoria) {
            $query->where('categoria', $this->categoria);
        }

        $this->total = $query->count();
        $this->noticias = $query->orderBy('published_at', $this->orden)->take($this->porPagina)->get();
    }


    /**
     * Renderiza la vista del componente.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.filtrar-noticias');
    }
}
