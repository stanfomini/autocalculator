<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Blog extends Model
{
    use HasFactory;

    protected $table = 'blogs';

    protected $fillable = [
        'title',
        'content',
    ];

      public function getIsNewAttribute(): bool
    {
        return $this->created_at
            && $this->created_at->gt(Carbon::now()->subMinutes(10));
    }
}