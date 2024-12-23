<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
	use HasFactory;

	protected $fillable = [
        'team_id',
        'name',
        'email',
        'phone_number',
        'address',
        'is_active',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function messages()
    {
        return $this->morphMany(Message::class, 'sender');
    }



}

