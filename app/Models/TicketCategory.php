<?php
// app/Models/TicketCategory.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'color',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relación con tickets
    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'category_id');
    }

    // Scope para categorías activas
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
