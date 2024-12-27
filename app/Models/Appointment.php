<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'phone',
        'appointment_datetime',
    ];

    // For green indicator if created < 10 minutes ago
    public function getIsNewAttribute(): bool
    {
        return $this->created_at
            && $this->created_at->gt(Carbon::now()->subMinutes(10));
    }
}