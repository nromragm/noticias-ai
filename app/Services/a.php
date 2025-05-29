<?php

namespace App\Services;

use App\Models\Noticias;
use Illuminate\Support\Facades\Http;
use OpenAI\Laravel\Facades\OpenAI;
use jcobhams\NewsApi\NewsApi;

class NewsApiService
{
    public function fetchAndStore()
    {
        $newsApi = new NewsApi(config('services.newsapi.key'));

        $categorias = $newsApi->getCategories();

        if (!$categorias) {
            throw new \Exception('Error al obtener categorías de NewsAPI.');
            
        } else {
            foreach ($categorias as $categoria) {

                $noticias = $newsApi->getTopHeadlines(null, null, null, $categoria, 20, 1);

                

                 // Maneja $topHeadlines: por ejemplo guardarlos o procesarlos
                if ($noticias && isset($noticias->articles)) {
                    foreach ($noticias->articles as $article) {
                        
                    }

                } else {
                    throw new \Exception('Error al obtener noticias de NewsAPI para la categoría: ' . $categoria);
                }
            }
        }

        $response = Http::get('https://newsapi.org/v2/top-headlines', [
            'apiKey' => config('services.newsapi.key'),
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

            $noticia = Noticias::where('url', $article['url'])->first();

            if (!$noticia) {
                // Solo generar contenido si no existe la noticia
                $prompt = "Redacta una noticia a partir del siguiente título y descripción. Devuelve solo un JSON con dos campos: \"contenido\" y \"categoria\". La categoría debe ser una de: deportes, politica, tecnologia, cultura, salud.\n\n"
                . "Título: {$article['title']}\n"
                . "Descripción: {$article['description']}\n\n"
                . "Ejemplo de respuesta: {\"contenido\": \"Texto redactado aquí...\", \"categoria\": \"deportes\"}";


                $response = Http::withToken(config('services.openai.key'))
                    ->post('https://api.openai.com/v1/chat/completions', [
                        'model' => 'gpt-4o',
                        'messages' => [
                            ['role' => 'user', 'content' => $prompt]
                        ],
                        'max_tokens' => 800,
                        'temperature' => 0.7,
                    ]);

                $rawContent = $response->json('choices.0.message.content') ?? '{}';

                // Intentar decodificar la respuesta como JSON
                $data = json_decode($rawContent, true);

                if (json_last_error() !== JSON_ERROR_NONE) {
                    // Si no es JSON válido, fallback a contenido simple y categoría default
                    $contenido = $rawContent;
                    $categoria = 'sincategoria';
                } else {
                    $contenido = $data['contenido'] ?? 'Contenido no disponible';
                    $categoria = $data['categoria'] ?? 'sincategoria';
                }

                Noticias::create([
                    'titulo' => $article['title'],
                    'descripcion' => $article['description'],
                    'url' => $article['url'],
                    'urlImg' => $article['urlToImage'],
                    'source' => $article['source']['name'] ?? null,
                    'published_at' => date('Y-m-d H:i:s', strtotime($article['publishedAt'])),
                    'contenido' => $contenido,
                    'categoria' => in_array($categoria, Noticias::CATEGORIAS) ? $categoria : 'sincategoria',
                ]);
            }

            // Si la noticia ya existe, no hacemos nada
        }
    }
}
