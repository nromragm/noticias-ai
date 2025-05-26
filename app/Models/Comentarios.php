<?php

namespace App\Models;

use App\Models\Noticias;
use App\Models\User;

use Illuminate\Database\Eloquent\Model;

class Comentarios extends Model
{

    use \Illuminate\Database\Eloquent\Factories\HasFactory;
    protected $fillable = [
        'user_id',
        'noticia_id',
        'comentario',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function noticia()
    {
        return $this->belongsTo(Noticias::class, 'noticia_id');
    }
}
