<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Noticias extends Model
{
    protected $fillable = [
        'titulo',
        'descripcion',
        'url',
        'urlImg',
        'contenido',
        'source',
        'published_at',
    ];

    public function comentarios()
    {
        return $this->hasMany(Comentarios::class, 'noticia_id');
    }
}
