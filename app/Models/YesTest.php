<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class YesTest extends Model
{
    use HasFactory;

    protected $table = 'yestests';

    protected $fillable = [
        'first_name',
        'last_name',
        'phone',
        'scheduled_at',
    ];

    public function getIsNewAttribute(): bool
    {
        return $this->created_at
            && $this->created_at->gt(Carbon::now()->subMinutes(10));
    }
}