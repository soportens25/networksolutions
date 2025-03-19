<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    use HasFactory;

    // Indica el nombre de la tabla que se debe utilizar
    protected $table = 'inventario';

    /**
     * Los atributos que se pueden asignar masivamente.
     *
     * @var array
     */
    protected $fillable = [
        'id_empresa',
        'nombre_equipo',
        'sticker',
        'marca_equipo',
        'tipo_equipo',
        'sistema_operativo',
        'numero_serial',
        'idioma',
        'procesador',
        'velocidad_procesador',
        'tipo_conexion',
        'ip',
        'mac',
        'memoria_ram',
        'cantidad_memoria',
        'slots_memoria',
        'frecuencia_memoria',
        'version_bios',
        'cantidad_discos',
        'tipo_discos',
        'espacio_discos',
        'grafica',
        'licencias',
        'perifericos',
        'observaciones',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'id_empresa');
    }
    public function historialMantenimiento()
    {
        return $this->hasMany(Historial_mantenimiento::class, 'id_equipo');
    }

    public function asignaciones()
    {
        return $this->hasMany(Personal_encargado::class, 'id_equipo');
    }
}
