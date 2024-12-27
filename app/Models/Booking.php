<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Booking extends Model
{
    use HasFactory;

    protected $table = 'bookings'; // The newly created table

    protected $fillable = [
        'first_name',
        'last_name',
        'phone',
        'booking_datetime',
    ];

    // For highlighting ?new? bookings if created < 10 minutes ago
    public function getIsNewAttribute(): bool
    {
        return $this->created_at
            && $this->created_at->gt(Carbon::now()->subMinutes(10));
    }
}