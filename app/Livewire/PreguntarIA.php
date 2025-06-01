<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Http;
use App\Models\Noticias;
use Livewire\Component;

class PreguntarIA extends Component
{
    public $noticia;
    public $pregunta;
    public $respuesta;

    public function mount($noticia)
    {
        $this->noticia = $noticia;
    }

    public function preguntar()
    {
        $contenido = "Noticia: {$this->noticia->titulo}\n{$this->noticia->descripcion}\n\nPregunta: {$this->pregunta}";
        $user = auth()->user();
        $modelo = $user->is_premium ? 'gpt-4-turbo' : 'gpt-3.5-turbo';

        $response = Http::withToken(config('services.openai.key'))
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => $modelo,
                'max_tokens' => 1000,
                'messages' => [
                    ['role' => 'user', 'content' => $contenido]
                ],
            ]);

        if ($response->successful()) {
            $respuesta = $response['choices'][0]['message']['content'] ?? 'Sin respuesta de la IA';
        } else {
            // Depura el error
            logger()->error('Error IA:', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            $respuesta = 'No se pudo obtener respuesta de la IA.';
        }
        $this->respuesta = $respuesta;
    }

    public function render()
    {
        return view('livewire.preguntar-i-a');
    }
}
