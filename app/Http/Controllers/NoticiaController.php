<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Noticias;
use App\Models\Comentarios;
use App\Models\User;
use App\Services\NewsApiService;


class NoticiaController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function show(Noticias $noticia)
    {
        $comentarios = $noticia->comentarios()->with('user')->latest()->get();
        return view('noticias.show', compact('noticia', 'comentarios'));
    }


    //si el usuario es admin, puede pueda importar noticias desde la API, si no, redirige a la página de inicio
    public function importarDesdeApi(NewsApiService $service)
    {
        if (!auth()->check() || !auth()->user()->is_admin) {
            return redirect()->route('noticias.index')->with('error', 'No tienes permiso para realizar esta acción.');
        }
        
        // Llama al servicio para obtener y almacenar noticias
        $service->fetchAndStore();
        return back()->with('success', 'Noticias importadas correctamente.');
    }

    
    // public function borrar(Noticias $noticia)
    // {
    //     if (auth()->check() && (auth()->user()->is_admin)) {
    //         $noticia->delete();
    //         return redirect()->route('noticias.index')->with('success', 'Noticia eliminada correctamente.');
    //     }
    //     return redirect()->route('noticias.index')->with('error', 'No tienes permiso para eliminar esta noticia.');
    // }

}
