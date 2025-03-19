<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Historial_mantenimiento extends Model
{
    use HasFactory;
    protected $table = 'historial_mantenimiento';
    protected $fillable = ['id_equipo', 'encargado', 'tipo_mantenimiento', 'fecha_mantenimiento', 'descripcion', 'observaciones'];
}
