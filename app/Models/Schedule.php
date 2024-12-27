<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Schedule extends Model
{
    use HasFactory;

    protected $table = 'schedules';  // Must match the migration

    // Fillable columns for mass assignment
    protected $fillable = [
        'first_name',
        'last_name',
        'phone',
        'scheduled_at',
    ];

    // If this record was created < 10 minutes ago, mark as "new"
    public function getIsNewAttribute(): bool
    {
        return $this->created_at 
            && $this->created_at->gt(Carbon::now()->subMinutes(10));
    }
}