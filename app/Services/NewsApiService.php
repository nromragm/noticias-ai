<?php

namespace App\Services;

use App\Models\Noticias;
use Illuminate\Support\Facades\Http;
use OpenAI\Laravel\Facades\OpenAI;

class NewsApiService
{
    public function fetchAndStore()
    {
        $response = Http::get('https://newsapi.org/v2/top-headlines', [
            'apiKey' => config('services.newsapi.key'),
            //'language' => 'es',
            'country' => 'us',
        ]);


        if (!$response->successful()) {
            throw new \Exception('Error al obtener noticias de NewsAPI.');
        }

        $articles = $response['articles'];

        foreach ($articles as $article) {
            
            if (
                !isset($article['title']) || trim($article['title']) === '' ||
                !isset($article['description']) || trim($article['description']) === '' ||
                !isset($article['url']) || trim($article['url']) === ''
            ) {
                continue; // saltar si falta alguno
            }

            // Generar contenido con OpenAI
            $prompt = "Redacta una noticia a partir del siguiente título y descripción:\n\n"
                . "Título: {$article['title']}\n"
                . "Descripción: {$article['description']}";

            $response = Http::withToken(config('services.openai.key'))
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-4o',
                    'messages' => [
                        ['role' => 'user', 'content' => $prompt]
                    ],
                    'max_tokens' => 600,
                    'temperature' => 0.7,
                ]);

            $contenido = $response->json('choices.0.message.content') ?? 'Contenido no disponible';


            Noticias::updateOrCreate(
                ['url' => $article['url']], // clave única
                [
                    'titulo' => $article['title'],
                    'descripcion' => $article['description'],
                    'urlImg' => $article['urlToImage'],
                    'source' => $article['source']['name'] ?? null,
                    'published_at' => date('Y-m-d H:i:s', strtotime($article['publishedAt'])),
                    'contenido' => $contenido, // contenido generado por OpenAI
                ]
            );
        }
    }
}
