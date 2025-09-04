<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    use HasFactory;
    protected $fillable = ['servicio', 'tipo', 'especificacion', 'imagen', 'imagen1', 'imagen2', 'imagen3'];
}
