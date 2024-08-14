<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Categoria extends Model
{
    use HasFactory;

    protected $table = 'categorias';

    protected $fillable = [
        'name',
        'descripcion',
    ];

    public $timestamps = true;

    public function noticias()
    {
        return $this->belongsToMany(Noticia::class, 'categoriadenoticia', 'idCategoria', 'idNoticia');
    }

    public function noticias2()
    {
        return $this->hasMany(Noticia::class);
    }

    public function up()
    {
        Schema::create('categorias', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('descripcion')->nullable();
            $table->timestamps();
        });
    }
    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
