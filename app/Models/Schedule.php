<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Schedule extends Model
{
    use HasFactory;

    // Corresponds to schedules table
    protected $table = 'schedules';

    // Fillable columns
    protected $fillable = [
        'first_name',
        'last_name',
        'phone',
        'scheduled_at',
    ];

    // If created < 10 minutes ago => "is_new" = true
    public function getIsNewAttribute(): bool
    {
        return $this->created_at
            && $this->created_at->gt(Carbon::now()->subMinutes(10));
    }
}