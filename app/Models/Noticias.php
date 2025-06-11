<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Noticias extends Model
{
    use HasFactory;
    
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
        'urlVideo',
        'contenido',
        'source',
        'published_at',
        'categoria',
        'img'
    ];

    public function comentarios()
    {
        return $this->hasMany(Comentarios::class, 'noticia_id');
    }

    public function valoraciones()
    {
        return $this->hasMany(Valoracion::class, 'noticia_id');
    }

    public function valoracionPromedio()
    {
        return $this->valoraciones()->avg('valor');
    }

    public function relacionadas($limit = 3)
    {
        return self::where('id', '!=', $this->id)
            ->where('categoria', $this->categoria)
            ->inRandomOrder()
            ->limit($limit)
            ->get();
    }
}
