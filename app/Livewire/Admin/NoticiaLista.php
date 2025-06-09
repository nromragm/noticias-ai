<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Noticias;
use App\Services\NewsApiService;


class NoticiaLista extends Component
{
    protected $listeners = ['noticiaGuardada' => '$refresh'];

    /*
    * Elimina una noticia por su ID.
    *
    * @param int $id ID de la noticia a eliminar.
    */
    public function delete($id)
    {
        Noticias::findOrFail($id)->delete();
        session()->flash('success', 'Noticia eliminada.');
    }

    /**
     * Edita una noticia existente.
     * @param int $id ID de la noticia a editar.
     * Redirige al formulario de edición de noticias.
     */
    public function editar($id)
    {
        $this->dispatch('editarNoticia', $id)->to('admin.noticia-form');
    }

    public function render()
    {
        return view('livewire.admin.noticia-lista', [
            'noticias' => Noticias::latest()->get(),
        ]);
    }
}
