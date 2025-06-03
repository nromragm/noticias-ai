<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Noticias;
use App\Services\NewsApiService;

class NoticiaForm extends Component
{
    public $noticiaId = null;
    public $titulo = '';
    public $contenido = '';
    public $categoria = '';
    public $source = '';
    public $urlImg = '';

    protected $rules = [
        'titulo' => 'required|string|max:255',
        'contenido' => 'required|string',
        'categoria' => 'required|string|max:100',
        'source' => 'nullable|string|max:100',
        'urlImg' => 'nullable|url|max:255',
    ];


    /**
     * Importa noticias desde la API de NewsAPI.
     *
     * @param string $idioma Idioma de las noticias a importar ('en' o 'es').
     * 
     */
    public function importarNoticias($idioma = 'en')
    {
        set_time_limit(300); // Asegura que el script no se detenga por tiempo de ejecución
        try {
            $service = new NewsApiService();

            if ($idioma === 'es') {
                $agregadas = $service->fetchAndStoreSpanish();
            } else {
                $agregadas = $service->fetchAndStore();
            }

            if ($agregadas === 0) {
                session()->flash('error', 'No hay noticias nuevas en la API que añadir');
            } else {
                session()->flash('success', "Noticias importadas correctamente: $agregadas");
            }
            $this->dispatch('$refresh');

        } catch (\Exception $e) {
            session()->flash('error', 'Error al importar noticias: ' . $e->getMessage());
        }
    }

    
    public function save()
    {
        $this->validate();

        Noticias::create([
            'titulo' => $this->titulo,
            'contenido' => $this->contenido,
            'categoria' => $this->categoria,
            'source' => $this->source,
            'urlImg' => $this->urlImg,
        ]);

        $this->reset(['titulo', 'contenido', 'categoria', 'source', 'urlImg']);
        $this->dispatch('noticiaGuardada');
        session()->flash('success', 'Noticia añadida correctamente.');
    }

    public function render()
    {
        return view('livewire.admin.noticia-form');
    }
}
