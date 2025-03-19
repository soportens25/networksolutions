<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personal_encargado extends Model
{
    use HasFactory;
    protected $table = 'personal_encargado';
    protected $fillable = ['id_equipo', 'usuario_responsable', 'area_ubicacion', 'fecha_asignacion', 'fecha_devolucion', 'observacion'];
}
