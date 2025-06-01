{{-- filepath: resources/views/errors/403.blade.php --}}
@extends('layouts.error')

@section('title', 'Sin permiso')

@section('content')
    <div class="flex flex-col items-center justify-center min-h-[85vh] dark:bg-gray-900 transition-colors">
        <h1 class="text-[10rem] font-extrabold text-blue-600 dark:text-blue-400 mb-8 drop-shadow-lg">403</h1>
        <p class="text-3xl md:text-4xl font-semibold text-gray-700 dark:text-gray-100 mb-8 text-center">
            No tienes permiso para acceder a esta página.
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