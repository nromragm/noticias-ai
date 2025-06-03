<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\NewsApiService;

class FetchNoticias extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'noticias:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Obtiene y guarda noticias en español desde NewsAPI';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $newsApi = new NewsApiService();
            $newsApi->fetchAndStoreSpanish();
            $this->info('✅ Noticias actualizadas correctamente.');
        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
        }
    }
}
