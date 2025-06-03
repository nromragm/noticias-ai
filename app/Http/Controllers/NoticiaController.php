<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Noticias;
use App\Models\Comentarios;
use App\Models\User;
use App\Services\NewsApiService;


class NoticiaController extends Controller
{

    // Devuelve la vista principal de noticias
    public function index()
    {
        return view('index');
    }

    // Devuelve la vista de noticia en concreto y sus comentarios
    public function show(Noticias $noticia)
    {
        $comentarios = $noticia->comentarios()->with('user')->latest()->get();
        return view('noticias.show', compact('noticia', 'comentarios'));
    }


    // Si el usuario es admin, puede importar noticias desde la API, si no, redirige a la página de inicio
    public function importarDesdeApi(NewsApiService $service)
    {
        if (!auth()->check() || !auth()->user()->is_admin) {
            return redirect()->route('noticias.index')->with('error', 'No tienes permiso para realizar esta acción.');
        }
        
        // Llama al servicio para obtener y almacenar noticias
        $service->fetchAndStore();
        return back()->with('success', 'Noticias importadas correctamente.');
    }
}
