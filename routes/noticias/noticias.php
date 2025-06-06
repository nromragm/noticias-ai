<?php


use Illuminate\Support\Facades\Route;
use App\Livewire\NoticiaShow;
use App\Http\Controllers\NoticiaController;

Route::prefix('/')->group(function () {
    Route::get('/', [NoticiaController::class, 'index'])->name('noticias.index');
    // routes/web.php
    Route::get('/noticias/{noticia}', NoticiaShow::class)->name('noticias.show');


});