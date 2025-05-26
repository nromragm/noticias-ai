<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\NewsApiService;

class FetchNews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'news:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Obtiene y guarda noticias desde NewsAPI';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $newsApi = new NewsApiService();
            $newsApi->fetchAndStore();
            $this->info('✅ Noticias actualizadas correctamente.');
        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
        }
    }
}
