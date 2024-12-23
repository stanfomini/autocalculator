<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Jetstream\Events\TeamCreated;
use Laravel\Jetstream\Events\TeamDeleted;
use Laravel\Jetstream\Events\TeamUpdated;
use Laravel\Jetstream\Team as JetstreamTeam;
use Illuminate\Support\Str;
class Team extends JetstreamTeam
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
	'slug',
	'personal_team',
	'slug',
       	'address',
       	'phone',
       	'logo',
       	'location',
       	'description',
       	'insurance_document',
       	'license_document',
       	'associations',
    ];

    /**
     * The event map for the model.
     *
     * @var array<string, class-string>
     */
    protected $dispatchesEvents = [
        'created' => TeamCreated::class,
        'updated' => TeamUpdated::class,
        'deleted' => TeamDeleted::class,
    ];

    protected static function booted()
{
    static::creating(function ($team) {
        $team->slug = Str::slug($team->name . '-' . Str::random(6));
    });
}

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'personal_team' => 'boolean',
        ];
    }

    public function customers()
{
    return $this->hasMany(Customer::class);
}

public function projects()
{
    return $this->hasMany(Project::class);
}

public function messages()
{
    return $this->hasMany(Message::class);
}


}
