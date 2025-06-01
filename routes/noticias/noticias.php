<?php


use Illuminate\Support\Facades\Route;
use App\Livewire\NoticiaShow;
use App\Http\Controllers\NoticiaController;

Route::prefix('/')->group(function () {
    Route::get('/', [NoticiaController::class, 'index'])->name('noticias.index');
    // routes/web.php
    Route::get('/noticias/{noticia}', NoticiaShow::class)->name('noticias.show');

    // Importar noticias desde la API
    // Route::post('/noticias/importar', [NoticiaController::class, 'importarDesdeApi'])
    //     ->name('noticias.importar')
    //     ->middleware('auth');

    // Borrar noticia
    // Route::delete('/noticias/{noticia}', [NoticiaController::class, 'borrar'])
    //     ->name('noticias.borrar')
    //     ->middleware('auth');
});