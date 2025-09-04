<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    protected $fillable = ['nombre_cliente', 'direccion_cliente', 'documento_cliente', 'telefono_cliente', 'correo_cliente', 'fecha_emision', 'subtotal', 'total', 'metodo_pago'];
    use HasFactory;
    
}
