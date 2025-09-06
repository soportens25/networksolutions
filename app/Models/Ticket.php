<?php
// app/Models/Ticket.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'technician_id',
        'category_id',
        'title',
        'description',
        'status',
        'priority',
        'assigned_at',
        'resolved_at',
        'last_viewed_by_technician',
        'metadata',
        'empresa_id'
    ];

        public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    protected $casts = [
        'assigned_at' => 'datetime',
        'resolved_at' => 'datetime',
        'last_viewed_by_technician' => 'datetime',
        'metadata' => 'array',
    ];

    // Relaciones
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    public function category()
    {
        return $this->belongsTo(TicketCategory::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function attachments()
    {
        return $this->hasMany(TicketAttachment::class);
    }

    public function activities()
    {
        return $this->hasMany(TicketActivity::class);
    }

    // Scopes
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeAssignedTo($query, $technicianId)
    {
        return $query->where('technician_id', $technicianId);
    }

    public function scopeUnassigned($query)
    {
        return $query->whereNull('technician_id');
    }

    // Accessors
    public function getStatusLabelAttribute()
    {
        return [
            'pending' => 'Pendiente',
            'assigned' => 'Asignado',
            'in_progress' => 'En Proceso',
            'resolved' => 'Resuelto',
            'closed' => 'Cerrado',
        ][$this->status] ?? 'Desconocido';
    }

    public function getPriorityLabelAttribute()
    {
        return [
            'low' => 'Baja',
            'medium' => 'Media',
            'high' => 'Alta',
            'urgent' => 'Urgente',
        ][$this->priority ?? 'medium'] ?? 'Media';
    }

    public function getResolutionTimeAttribute()
    {
        if (!$this->resolved_at) {
            return null;
        }

        return $this->created_at->diffInHours($this->resolved_at);
    }

    // Métodos útiles
    public function canBeAssignedTo($technician)
    {
        return $this->technician_id === null &&
            $technician->hasRole('tecnico') &&
            $this->status === 'pending';
    }


    public function isOverdue()
    {
        // Por ahora retornamos false, después puedes implementar lógica de SLA
        return false;
    }
}
