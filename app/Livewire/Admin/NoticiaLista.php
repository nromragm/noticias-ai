<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Noticias;
use App\Services\NewsApiService;


class NoticiaLista extends Component
{
    protected $listeners = ['noticiaGuardada' => '$refresh'];

    public function delete($id)
    {
        Noticias::findOrFail($id)->delete();
        session()->flash('success', 'Noticia eliminada.');
    }

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
