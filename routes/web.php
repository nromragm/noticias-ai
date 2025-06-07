<?php

use Illuminate\Support\Facades\Route;

Route::view('/aboutNoticIA', 'aboutNoticIA')
    ->name('aboutNoticIA');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
require __DIR__ . '/noticias/noticias.php';
require __DIR__ . '/suscripciones/suscripciones.php';
require __DIR__.'/admin/admin.php';