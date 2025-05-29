<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Noticias extends Model
{
    public const CATEGORIAS = [
        'business',
        'entertainment',
        'general',
        'health',
        'science',
        'sports',
        'technology',
    ];

    protected $fillable = [
        'titulo',
        'descripcion',
        'url',
        'urlImg',
        'contenido',
        'source',
        'published_at',
        'categoria',
    ];

    public function comentarios()
    {
        return $this->hasMany(Comentarios::class, 'noticia_id');
    }

    
}
