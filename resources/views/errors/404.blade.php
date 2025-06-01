{{-- filepath: resources/views/errors/404.blade.php --}}
@extends('layouts.error')

@section('title', 'Página no encontrada')

@section('content')
    <div class="flex flex-col items-center justify-center min-h-[70vh] bg-white dark:bg-gray-900 transition-colors">
        <h1 class="text-[10rem] font-extrabold text-blue-600 dark:text-blue-400 mb-8 drop-shadow-lg">404</h1>
        <p class="text-3xl md:text-4xl font-semibold text-gray-700 dark:text-gray-100 mb-8 text-center">
            La página que buscas no existe.
        </p>
        <a href="{{ url('/') }}"
            class="bg-blue-600 dark:bg-blue-500 text-white px-10 py-4 rounded-xl text-xl font-bold shadow
                    hover:bg-blue-700 dark:hover:bg-blue-700
                    hover:text-white dark:hover:text-white
                    transition">
                Volver al inicio
        </a>
    </div>
@endsection