<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class UserManagementController extends Controller
{
    public function registerTeamMember($teamId, $email, $role = 'editor')
{
    // Generate a temporary password
    $temporaryPassword = Str::random(10);

    // Create the new user
    $user = User::create([
        'name' => 'New Team Member',  // You can customize this
        'email' => $email,
        'password' => Hash::make($temporaryPassword),
    ]);

    // Assign the user to the team
    $team = Team::find($teamId);
    $team->users()->attach($user, ['role' => $role]);

    // Send email with the temporary password
    Mail::to($email)->send(new \App\Mail\TeaTeamInvitation($temporaryPassword));
}
}
