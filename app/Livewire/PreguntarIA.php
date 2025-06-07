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


    /**
     * Método para preguntar a la IA sobre la noticia.
     * Utiliza el modelo de OpenAI para generar una respuesta basada en la noticia y la pregunta del usuario.
     */
    public function preguntar()
    {
        $contenido = "Noticia: {$this->noticia->titulo}\n{$this->noticia->contenido}\n\nPregunta: {$this->pregunta}";
        $user = auth()->user();
        $modelo = ($user && $user->is_premium) ? 'gpt-4-turbo' : 'gpt-3.5-turbo';

        // Elige el modelo de OpenAI en función del tipo de usuario: los usuarios premium usan GPT-4 Turbo, los demás usan GPT-3.5 Turbo
        $response = Http::withToken(config('services.openai.key'))
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => $modelo,
                'max_tokens' => 1000,
                'messages' => [
                    ['role' => 'user', 'content' => $contenido]
                ],
            ]);

        if ($response->successful()) {
            // Extrae la respuesta generada por la IA del cuerpo de la respuesta
            $respuesta = $response['choices'][0]['message']['content'] ?? 'Sin respuesta de la IA';
        } else {
            // Depura el error
            logger()->error('Error IA:', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            // Mensaje por defecto en caso de error
            $respuesta = 'No se pudo obtener respuesta de la IA.';
        }
        $this->respuesta = $respuesta;
    }

    public function render()
    {
        return view('livewire.preguntar-i-a');
    }
}
