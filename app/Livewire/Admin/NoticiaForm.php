<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Noticias;
use App\Services\NewsApiService;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class NoticiaForm extends Component
{
    use WithFileUploads;

    
    public $noticiaId = null;
    public $titulo = '';
    public $descripcion = '';
    public $contenido = '';
    public $categoria = '';
    public $source = '';
    public $urlImg = '';
    public $urlVideo = '';
    public $img;
    public $categorias = [];

    
    // Esta función define las reglas de validación para los campos del formulario
    protected function rules()
    {
        return [
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string|max:255',
            'contenido' => 'required|string',
            'categoria' => 'required|string|max:100',
            'source' => 'required|string|max:100',
            'urlImg' => 'nullable|url|max:255',
            'img' => 'nullable|image|max:2048',
            'urlVideo' => [
                'nullable',
                'url',
                'max:255',
                function ($attribute, $value, $fail) {
                    if ($value && !preg_match('/^https:\/\/(www\.)?youtube\.com\/embed\/[a-zA-Z0-9_-]+$/', $value)) {
                        $fail('La URL del vídeo debe ser una URL de YouTube en formato embed.');
                    }
                }
            ],
        ];
    }

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
            $this->urlVideo = $noticia->urlVideo;
            if ($noticia->urlImg) {
                $this->img = $noticia->urlImg; // Carga la imagen existente si está disponible
            }
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
        $this->urlVideo = $noticia->urlVideo;
        if ($noticia->urlImg) {
            $this->img = $noticia->urlImg; // Carga la imagen existente si está disponible
        }
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
        $this->validate($this->rules());

        try {
            
            $imgPath = null;
            if ($this->img && is_object($this->img)) {
                $imgPath = $this->img->store('noticias', 'public');
            } elseif (is_string($this->img)) {
                $imgPath = $this->img;
            }

            if ($this->noticiaId) {
                $noticia = \App\Models\Noticias::findOrFail($this->noticiaId);
                $noticia->update([
                    'titulo' => $this->titulo,
                    'descripcion' => $this->descripcion,
                    'contenido' => $this->contenido,
                    'categoria' => $this->categoria,
                    'source' => $this->source,
                    'urlImg' => $this->urlImg ?: null,
                    'urlVideo' => $this->urlVideo,
                    'img' => $imgPath,
                ]);
                session()->flash('success', 'Noticia actualizada correctamente.');
            } else {
                \App\Models\Noticias::create([
                    'titulo' => $this->titulo,
                    'descripcion' => $this->descripcion,
                    'contenido' => $this->contenido,
                    'categoria' => $this->categoria,
                    'source' => $this->source,
                    'urlImg' => $this->urlImg ?: null,
                    'urlVideo' => $this->urlVideo,
                    'img' => $imgPath,
                    'published_at' => now(),
                ]);
                session()->flash('success', 'Noticia añadida correctamente.');
            }

            $this->reset(['noticiaId', 'titulo', 'descripcion', 'contenido', 'categoria', 'source', 'urlImg', 'urlVideo', 'img']);
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
