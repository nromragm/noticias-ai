    public function fetchAndStoreSpanish()
    {
        $newsApi = new NewsApi(config('services.newsapi.key'));
        $categorias = $newsApi->getCategories();

        $language = 'es'; // Español
        $country = 'es'; // España

        if (!$categorias || !is_array($categorias)) {
            \Log::error("Error al obtener categorías de NewsAPI.");
            throw new \Exception('Error al obtener categorías de NewsAPI.');
        }

        foreach ($categorias as $categoria) {
            $sourcesResponse = $newsApi->getSources($categoria, $language, null);

            if (
                !$sourcesResponse ||
                !isset($sourcesResponse->sources) ||
                !is_array($sourcesResponse->sources) ||
                empty($sourcesResponse->sources)
            ) {
                \Log::error("Error al obtener fuentes de NewsAPI.");
                continue;
            }

            foreach ($sourcesResponse->sources as $source) {

                $noticias = $newsApi->getEverything(
                    null,    // $q
                    $source->id,    // $sources
                    null,    // $domains
                    null,    // $exclude_domains
                    null,    // $from
                    null,    // $to
                    $language,    // $language
                    null,    // $sortBy
                    2,      // $pageSize
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
                        

                        Noticias::create([
                            'titulo' => $article->title,
                            'descripcion' => $article->description,
                            'url' => $article->url,
                            'urlImg' => $article->urlToImage,
                            'source' => $source->name ?? null,
                            'published_at' => date('Y-m-d H:i:s', strtotime($article->publishedAt)),
                            'contenido' => $contenido,
                            'categoria' => $categoria ?? 'general', // Asignar categoría o 'general' si no se especifica
                        ]);
                    }
                } else {
                    \Log::error("Error al obtener noticias de la fuente: {$source->name}");
                    throw new \Exception('Error al obtener noticias de NewsAPI para la fuente: ' . $source->name);
                }
            }
        }
    }