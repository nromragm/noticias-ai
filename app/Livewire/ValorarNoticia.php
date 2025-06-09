<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Noticias;
use App\Models\Valoracion;
use Illuminate\Support\Facades\Auth;

class ValorarNoticia extends Component
{
public Noticias $noticia;

    public $valor = null;

    public function mount(Noticias $noticia)
    {
        $this->noticia = $noticia;
        if (Auth::check()) {
            $valoracion = Valoracion::where('user_id', Auth::id())
                ->where('noticia_id', $noticia->id)
                ->first();
            if ($valoracion) {
                $this->valor = $valoracion->valor;
            }
        }
    }

    /**
     * Valorar la noticia.
     * Validar que el usuario ha iniciado sesión y que el valor es un número entre 1 y 5.
     * Si el usuario ya ha valorado la noticia, actualizar su valoración.
     * Si no, crear una nueva valoración.
     */
    public function valorar()
    {
        $this->validate([
            'valor' => 'required|integer|min:1|max:5',
        ]);

        Valoracion::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'noticia_id' => $this->noticia->id,
            ],
            [
                'valor' => $this->valor,
            ]
        );

        session()->flash('success', '¡Gracias por tu valoración!');
    }

    public function render()
    {
        $promedio = $this->noticia->valoracionPromedio();
        return view('livewire.noticias.valorar-noticia', [
            'promedio' => $promedio,
        ]);
    }
}
