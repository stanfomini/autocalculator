<?php

namespace App\Models;

// typical user model after installing fortify, includes traits
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use App\Models\AwesomeCalc;

class User extends Authenticatable
{
    use HasApiTokens, HasProfilePhoto, Notifiable, TwoFactorAuthenticatable;

    protected $fillable = [
        'name',
        'email',
        'password',
        // etc.
    ];

    protected $hidden = [
        'password',
        'remember_token',
        // etc.
    ];

    // each user can have many calculators
    public function awesomeCalcs()
    {
        return $this->hasMany(AwesomeCalc::class);
    }
}