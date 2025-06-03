<?php

namespace App\Services;

use App\Models\Noticias;
use Illuminate\Support\Facades\Http;
use OpenAI\Laravel\Facades\OpenAI;
use jcobhams\NewsApi\NewsApi;


class NewsApiService
{
    /**
     * Realiza la consulta a NewsAPI y almacena las noticias en la base de datos.
     * @return int Número de noticias agregadas
     * @throws \Exception Si hay un error al obtener las categorías o noticias
     * @throws \Exception Si hay un error al procesar la respuesta de OpenAI
     * @throws \Exception Si hay un error al guardar las noticias en la base de datos
     */
    public function fetchAndStore()
    {
        $newsApi = new NewsApi(config('services.newsapi.key'));
        $categorias = $newsApi->getCategories();
        $noticiasAgregadas = 0;
        
        // Verifica que se obtuvieron las categorías correctamente
        if (!$categorias) {
            throw new \Exception('Error al obtener categorías de NewsAPI.');
        }

        // Foreach para obtener noticias de cada categoría
        foreach ($categorias as $categoria) {
            $noticias = $newsApi->getTopHeadlines(null, null, null, $categoria, 1);

            if ($noticias && isset($noticias->articles)) {
                foreach ($noticias->articles as $article) {

                    // Verifica que los campos necesarios no estén vacíos
                    // Si alguno de los campos está vacío, salta a la siguiente iteración
                    // Esto evita que se creen noticias incompletas en la base de datos
                    if (
                        empty($article->title) ||
                        empty($article->description) ||
                        empty($article->url) ||
                        empty($article->urlToImage)
                    ) {
                        continue;
                    }

                    // Verifica si la noticia ya existe en la base de datos, si es así, salta a la siguiente
                    if (Noticias::where('url', $article->url)->exists()) {
                        continue;
                    }

                    // Genera el contenido de la noticia usando OpenAI
                    $contenido = $this->generarContenido($article->title, $article->description);

                    // Crea la noticia en la base de datos
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

                    // Incrementa el contador de noticias agregadas
                    $noticiasAgregadas++;
                }
            } else {
                \Log::error("Error al obtener noticias para la categoría: $categoria");
                continue; // Sigue con la siguiente categoría
            }
        }
        return $noticiasAgregadas;
    }

    /**
     * Genera el contenido de la noticia usando OpenAI.
     *
     * @param string $titulo Título de la noticia
     * @param string $descripcion Descripción de la noticia
     * @return string Contenido generado por OpenAI
     */
    private function generarContenido($titulo, $descripcion)
    {
        
        try {
            // Prepara el prompt para OpenAI
            $prompt = "Redacta una noticia a partir del siguiente título y descripción. Devuelve solo un JSON con un campo: \"contenido\"\n\n"
                . "Título: {$titulo}\n"
                . "Descripción: {$descripcion}\n\n"
                . "Ejemplo de respuesta: {\"contenido\": \"Texto redactado aquí\"}";

            // Realiza la solicitud a OpenAI para generar el contenido
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

            // Verifica si la respuesta es exitosa    
            if (!$response->ok()) {
                \Log::error('Error en la respuesta de OpenAI', ['response' => $response->body()]);
                return 'Contenido no disponible';
            }

            // Extrae el contenido del JSON devuelto por OpenAI
            $rawContent = $response->json('choices.0.message.content') ?? '{}';

            // Limpia el contenido para eliminar las marcas de código y decodifica el JSON
            $rawContent = preg_replace('/^```json\s*|```$/m', '', trim($rawContent));
            $data = json_decode($rawContent, true);

            // Verifica si la decodificación fue exitosa y si el campo 'contenido' existe
            if (json_last_error() !== JSON_ERROR_NONE) {
                \Log::warning('Respuesta de OpenAI no es JSON válido', ['raw' => $rawContent]);
                return $rawContent;
            }

            // Si el campo 'contenido' no existe, devuelve un mensaje por defecto
            return $data['contenido'] ?? 'Contenido no disponible';

        } catch (\Throwable $e) {
            // Captura cualquier error de red, timeout, errores de Laravel, etc.
            \Log::error('Excepción al generar contenido con OpenAI', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return 'Error al generar el contenido.';
        }
    }


    /**
     * Obtiene noticias en español de varios dominios y las almacena en la base de datos.
     *
     * @return int Número de noticias agregadas
     * @throws \Exception Si hay un error al obtener noticias o al guardarlas
     * @throws \Exception Si hay un error al procesar la respuesta de OpenAI
     * @throws \Exception Si hay un error al guardar las noticias en la base de datos
     */
    public function fetchAndStoreSpanish()
    {
        set_time_limit(300); // Asegura que el script no se detenga por tiempo de ejecución
        // Lista de dominios de noticias en español
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

        // Obtiene noticias de cada dominio
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


            // Verifica si se obtuvieron noticias y si la estructura es correcta
            if ($noticias && isset($noticias->articles)) {
                foreach ($noticias->articles as $article) {
                    if (
                        empty($article->title) ||
                        empty($article->description) ||
                        empty($article->url) ||
                        empty($article->urlToImage)
                    ) {
                        // Si alguno de los campos necesarios está vacío, salta a la siguiente iteración
                        continue;
                    }

                    // Verifica si la noticia ya existe en la base de datos
                    if (Noticias::where('url', $article->url)->exists()) {
                        continue;
                    }

                    // Genera el contenido y la categoria de la noticia usando OpenAI
                    $contenido = $this->generarContenido($article->title, $article->description);
                    $categoria = $this->generarCategoria($article->title, $article->description);
                    if (!$categoria) {
                        $categoria = 'general'; // Fallback si no se puede determinar la categoría
                    }

                    // Crea la noticia en la base de datos
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

    /**
     * Genera automáticamente la categoría de una noticia usando OpenAI.
     *
     * @param string $titulo El título de la noticia.
     * @param string $descripcion La descripción de la noticia.
     * @return string Categoría generada (o 'general' si falla).
     */
    private function generarCategoria($titulo, $descripcion)
    {
        try {
            // Une todas las categorías definidas en el modelo Noticias en un string separado por comas
            $categorias = implode(', ', \App\Models\Noticias::CATEGORIAS);

            // Prepara el prompt que se enviará a OpenAI, incluyendo instrucciones claras y un ejemplo
            $prompt = "Clasifica la siguiente noticia en una sola categoría de esta lista: [$categorias]. " .
                "Devuelve solo un JSON con el campo \"categoria\" y el valor exacto de la categoría elegida.\n\n" .
                "Título: {$titulo}\n" .
                "Descripción: {$descripcion}\n\n" .
                "Ejemplo de respuesta: {\"categoria\": \"technology\"}";

            // Realiza la petición a la API de OpenAI
            $response = \Illuminate\Support\Facades\Http::timeout(60)
                ->withToken(config('services.openai.key'))
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-4o', // Modelo utilizado para la clasificación
                    'messages' => [
                        ['role' => 'user', 'content' => $prompt]
                    ],
                    'max_tokens' => 20,
                    'temperature' => 0, // 0 = máxima precisión (sin creatividad)
                ]);

            // Verifica si la respuesta fue exitosa
            if (!$response->ok()) {
                \Log::error('Error en la respuesta de OpenAI al clasificar categoría', [
                    'response' => $response->body()
                ]);
                return 'general'; // Categoría por defecto si algo falla
            }

            // Extrae el contenido JSON de la respuesta
            $rawContent = $response->json('choices.0.message.content') ?? '{}';

            // Intenta decodificar el contenido JSON
            $data = json_decode($rawContent, true);

            // Verifica que el JSON sea válido y contenga la clave "categoria"
            if (json_last_error() !== JSON_ERROR_NONE || empty($data['categoria'])) {
                \Log::warning('Respuesta de OpenAI no es JSON válido para categoría', ['raw' => $rawContent]);
                return 'general';
            }

            // Asegura que la categoría devuelta está dentro de las categorías válidas
            if (!in_array($data['categoria'], \App\Models\Noticias::CATEGORIAS)) {
                return 'general';
            }

            // Retorna la categoría generada correctamente
            return $data['categoria'];

        } catch (\Throwable $e) {
            // Captura cualquier excepción (problemas de red, errores de API, etc.)
            \Log::error('Excepción al generar categoría con OpenAI', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return 'general'; // Valor por defecto en caso de excepción
        }
    }
}


