<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Noticia extends Model
{
    use HasFactory;

    protected $fillable = ['titulo', 'texto', 'linkIMG'];

    public function categorias()
    {
        return $this->belongsToMany(Categoria::class, 'categoriadenoticia', 'idNoticia', 'idCategoria');
    }


}
