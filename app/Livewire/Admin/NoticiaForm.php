<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Noticias;
use App\Services\NewsApiService;

class NoticiaForm extends Component
{
    public $noticiaId = null;
    public $titulo = '';
    public $descripcion = '';
    public $contenido = '';
    public $categoria = '';
    public $source = '';
    public $urlImg = '';
    public $categorias = [];

    protected $rules = [
        'titulo' => 'required|string|max:255',
        'descripcion' => 'required|string|max:255',
        'contenido' => 'required|string',
        'categoria' => 'required|string|max:100',
        'source' => 'required|string|max:100',
        'urlImg' => 'required|url|max:255',
    ];

    protected $listeners = ['editarNoticia'];

    /**
     * Inicializa el componente con los datos de la noticia si se proporciona un ID.
     *
     * @param int|null $id ID de la noticia a editar, si es null se crea una nueva.
     */
    public function mount($id = null)
    {
        $this->categorias = \App\Models\Noticias::CATEGORIAS;

        if ($id) {
            $noticia = \App\Models\Noticias::findOrFail($id);
            $this->noticiaId = $noticia->id;
            $this->titulo = $noticia->titulo;
            $this->descripcion = $noticia->descripcion;
            $this->contenido = $noticia->contenido;
            $this->categoria = $noticia->categoria;
            $this->source = $noticia->source;
            $this->urlImg = $noticia->urlImg;
        }
    }

    /**
     * Carga los datos de la noticia para editar.
     *
     * @param int $id ID de la noticia a editar.
     */
    // Esta función se llama cuando se recibe el evento 'editarNoticia'
    public function editarNoticia($id)
    {
        $noticia = \App\Models\Noticias::findOrFail($id);
        $this->noticiaId = $noticia->id;
        $this->titulo = $noticia->titulo;
        $this->descripcion = $noticia->descripcion;
        $this->contenido = $noticia->contenido;
        $this->categoria = $noticia->categoria;
        $this->source = $noticia->source;
        $this->urlImg = $noticia->urlImg;
    }


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

    
    /**
     * Guarda la noticia, ya sea actualizando una existente o creando una nueva.
     *
     */
    public function save()
    {
        $this->validate();

        try {
            if ($this->noticiaId) {
                $noticia = \App\Models\Noticias::findOrFail($this->noticiaId);
                $noticia->update([
                    'titulo' => $this->titulo,
                    'descripcion' => $this->descripcion,
                    'contenido' => $this->contenido,
                    'categoria' => $this->categoria,
                    'source' => $this->source,
                    'urlImg' => $this->urlImg,
                ]);
                session()->flash('success', 'Noticia actualizada correctamente.');
            } else {
                \App\Models\Noticias::create([
                    'titulo' => $this->titulo,
                    'descripcion' => $this->descripcion,
                    'contenido' => $this->contenido,
                    'categoria' => $this->categoria,
                    'source' => $this->source,
                    'urlImg' => $this->urlImg,
                    'published_at' => now(),
                ]);
                session()->flash('success', 'Noticia añadida correctamente.');
            }

            $this->reset(['noticiaId', 'titulo', 'descripcion', 'contenido', 'categoria', 'source', 'urlImg']);
            $this->dispatch('noticiaGuardada');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al guardar la noticia: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.noticia-form');
    }
}
