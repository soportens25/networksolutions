<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'technician_id', 'title', 'description', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }
    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }
    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id'); // Asegúrate que el campo exista en la tabla tickets
    }
}
