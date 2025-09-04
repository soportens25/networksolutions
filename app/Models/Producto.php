<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = ['producto', 'descripcion', 'stock', 'precio', 'imagen', 'id_categoria', 'id_estado'];
    
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria');
    }

    // Relación con el estado
    public function estado()
    {
        return $this->belongsTo(Estado::class, 'id_estado');
    }
}