<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChecklistItem extends Model
{
	use HasFactory;


	protected $fillable = [
        'stage_id',
        'name',
        'is_completed',
        'completed_at',
        'assigned_to_user_id',
        'notes',
    ];

    public function stage()
    {
        return $this->belongsTo(Stage::class);
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to_user_id');
    }
}
