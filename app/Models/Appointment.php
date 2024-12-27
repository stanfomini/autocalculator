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

    // Example helper to check if appointment was created < 10 min ago
    public function getIsNewAttribute()
    {
        return $this->created_at && $this->created_at->gt(Carbon::now()->subMinutes(10));
    }
}