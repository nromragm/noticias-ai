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
        $noticiasAgregadas = 0;
        if (!$categorias) {
            throw new \Exception('Error al obtener categorías de NewsAPI.');
        }

        foreach ($categorias as $categoria) {
            $noticias = $newsApi->getTopHeadlines(null, null, null, $categoria, 1);

            if ($noticias && isset($noticias->articles)) {
                foreach ($noticias->articles as $article) {
                    if (
                        empty($article->title) ||
                        empty($article->description) ||
                        empty($article->url) ||
                        empty($article->urlToImage)
                    ) {
                        continue;
                    }

                    if (Noticias::where('url', $article->url)->exists()) {
                        continue;
                    }

                    $contenido = $this->generarContenido($article->title, $article->description);

                    Noticias::create([
                        'titulo' => $article->title,
                        'descripcion' => $article->description,
                        'url' => $article->url,
                        'urlImg' => $article->urlToImage,
                        'source' => $article->source->name ?? null,
                        'published_at' => date('Y-m-d H:i:s', strtotime($article->publishedAt)),
                        'contenido' => $contenido,
                        'categoria' => $categoria,
                    ]);
                    $noticiasAgregadas++;
                }
            } else {
                \Log::error("Error al obtener noticias para la categoría: $categoria");
                continue; // Sigue con la siguiente categoría
            }
        }
        return $noticiasAgregadas;
    }

    private function generarContenido($titulo, $descripcion)
    {
        $prompt = "Redacta una noticia a partir del siguiente título y descripción. Devuelve solo un JSON con un campo: \"contenido\"\n\n"
            . "Título: {$titulo}\n"
            . "Descripción: {$descripcion}\n\n"
            . "Ejemplo de respuesta: {\"contenido\": \"Texto redactado aquí\"}";

        $response = Http::timeout(120)
            ->withToken(config('services.openai.key'))
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4o',
                'messages' => [
                    ['role' => 'user', 'content' => $prompt]
                ],
                'max_tokens' => 500,
                'temperature' => 0.7,
            ]);

        if (!$response->ok()) {
            \Log::error('Error en la respuesta de OpenAI', ['response' => $response->body()]);
            return 'Contenido no disponible';
        }

        $rawContent = $response->json('choices.0.message.content') ?? '{}';

        // Limpia bloque Markdown si existe
        $rawContent = preg_replace('/^```json\s*|```$/m', '', trim($rawContent));

        $data = json_decode($rawContent, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            \Log::warning('Respuesta de OpenAI no es JSON válido', ['raw' => $rawContent]);
            return $rawContent;
        }

        return $data['contenido'] ?? 'Contenido no disponible';
    }


    public function fetchAndStoreSpanish()
    {
        $dominios = [
            'marca.com',
            'elmundo.es',
            'abc.es',
            'as.com',
            'lavanguardia.com',
            'elespanol.com',
            '20minutos.es',
            'expansion.com',
            'elconfidencial.com',
            'muyinteresante.es',
            'computerhoy.com',
            'elperiodico.com',
            'infosalus.com',
        ];
        $newsApi = new NewsApi(config('services.newsapi.key'));
        $noticiasAgregadas = 0;

        foreach ($dominios as $dominio) {
            $noticias = $newsApi->getEverything(
                null,    // $q
                null,    // $sources
                $dominio,    // $domains
                null,    // $exclude_domains
                null,    // $from
                null,    // $to
                'es',    // $language
                null,    // $sortBy
                3,      // $pageSize
                null     // $page
            );


            if ($noticias && isset($noticias->articles)) {
                foreach ($noticias->articles as $article) {
                    if (
                        empty($article->title) ||
                        empty($article->description) ||
                        empty($article->url) ||
                        empty($article->urlToImage)
                    ) {
                        continue;
                    }

                    if (Noticias::where('url', $article->url)->exists()) {
                        continue;
                    }

                    $contenido = $this->generarContenido($article->title, $article->description);
                    $categoria = $this->generarCategoria($article->title, $article->description);
                    if (!$categoria) {
                        $categoria = 'general'; // Fallback si no se puede determinar la categoría
                    }

                    Noticias::create([
                        'titulo' => $article->title,
                        'descripcion' => $article->description,
                        'url' => $article->url,
                        'urlImg' => $article->urlToImage,
                        'source' => $article->source->name ?? null,
                        'published_at' => date('Y-m-d H:i:s', strtotime($article->publishedAt)),
                        'contenido' => $contenido,
                        'categoria' => $categoria,
                    ]);
                    $noticiasAgregadas++;
                }
            } else {
                \Log::error("Error al obtener noticias en español desde NewsAPI para el dominio: $dominio");
                continue; // Sigue con el siguiente dominio
            }
        }
        return $noticiasAgregadas;
    }

    private function generarCategoria($titulo, $descripcion)
    {
        $categorias = implode(', ', \App\Models\Noticias::CATEGORIAS);

        $prompt = "Clasifica la siguiente noticia en una sola categoría de esta lista: [$categorias]. ".
            "Devuelve solo un JSON con el campo \"categoria\" y el valor exacto de la categoría elegida.\n\n".
            "Título: {$titulo}\n".
            "Descripción: {$descripcion}\n\n".
            "Ejemplo de respuesta: {\"categoria\": \"technology\"}";

        $response = \Illuminate\Support\Facades\Http::timeout(60)
            ->withToken(config('services.openai.key'))
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4o',
                'messages' => [
                    ['role' => 'user', 'content' => $prompt]
                ],
                'max_tokens' => 20,
                'temperature' => 0,
            ]);

        if (!$response->ok()) {
            \Log::error('Error en la respuesta de OpenAI al clasificar categoría', ['response' => $response->body()]);
            return 'general';
        }

        $rawContent = $response->json('choices.0.message.content') ?? '{}';
        $data = json_decode($rawContent, true);

        if (json_last_error() !== JSON_ERROR_NONE || empty($data['categoria'])) {
            \Log::warning('Respuesta de OpenAI no es JSON válido para categoría', ['raw' => $rawContent]);
            return 'general';
        }

        // Validar que la categoría devuelta está en la lista permitida
        if (!in_array($data['categoria'], \App\Models\Noticias::CATEGORIAS)) {
            return 'general';
        }

        return $data['categoria'];
    }
}


