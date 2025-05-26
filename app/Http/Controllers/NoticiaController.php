<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Noticias;
use App\Models\Comentarios;
use App\Models\User;


class NoticiaController extends Controller
{
    public function index()
    {
        $noticias = Noticias::latest()->take(10)->get(); // puedes ajustar el número
        return view('index', compact('noticias'));
    }

    public function show(Noticias $noticia)
    {
        $comentarios = $noticia->comentarios()->with('user')->latest()->get();
        return view('noticias.show', compact('noticia', 'comentarios'));
    }

}
