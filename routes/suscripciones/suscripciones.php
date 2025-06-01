<?php

use App\Http\Controllers\StripeController;
use Illuminate\Support\Facades\Route;


Route::prefix('suscripciones')
    ->middleware(['auth'])
    ->name('checkout.')
    ->group(function () {
        Route::get('/pago', [StripeController::class, 'checkout'])->name('iniciar');
        Route::get('/exito', [StripeController::class, 'success'])->name('exito');
        Route::get('/cancelado', [StripeController::class, 'cancel'])->name('cancelado');
    });