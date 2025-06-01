<?php
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('/admin', function () {
        abort_unless(auth()->user()->isAdmin(), 403);
        return view('panel-admin');
    })->name('admin');
});