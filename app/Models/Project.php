<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
	use HasFactory;


	protected $fillable = [
        'team_id',
        'customer_id',
        'name',
        'description',
        'start_date',
        'end_date',
        'is_archived',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function stages()
    {
        return $this->hasMany(Stage::class);
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function assignedUsers()
{
    return $this->belongsToMany(User::class, 'assignments');
}

}

