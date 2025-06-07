<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Valoracion extends Model
{
    protected $table = 'valoraciones';
    protected $fillable = ['user_id', 'noticia_id', 'valor'];

    public function noticia()
    {
        return $this->belongsTo(Noticias::class, 'noticia_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
