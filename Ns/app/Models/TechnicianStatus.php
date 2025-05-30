<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TechnicianStatus extends Model {
    use HasFactory;

    protected $table = 'technician_status';
    protected $fillable = ['user_id', 'status'];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
    
}
