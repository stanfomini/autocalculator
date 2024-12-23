<?php

namespace App\Actions\Jetstream;

use App\Models\Team;
use App\Models\User;
use Closure;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Jetstream\Contracts\InvitesTeamMembers;
use Laravel\Jetstream\Events\InvitingTeamMember;
use Laravel\Jetstream\Jetstream;
//use Laravel\Jetstream\Mail\TeamInvitation;
use App\Mail\TeamInvitation;
use Laravel\Jetstream\Rules\Role;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use DB;

class InviteTeamMember implements InvitesTeamMembers
{
    /**
     * Invite a new team member to the given team.
     */
	public function invite(User $user, Team $team, string $email, ?string $role = 'employee'): void
{
    // Authorize the current user to add a team member
    Gate::forUser($user)->authorize('addTeamMember', $team);

    // Validate the email and role
    $this->validate($team, $email, $role);

    // Dispatch an event for inviting the team member
    InvitingTeamMember::dispatch($team, $email, $role);

    // Check if the user exists; if not, create the user
    $invitedUser = User::where('email', $email)->first();
    
    $temporaryPassword = null;
    $token = null;

    // If user does not exist, create the user and set the temporary password
    if (!$invitedUser) {
        // If the user does not exist, create the user, set the team, and send the invite email
        DB::transaction(function () use ($team, $email, $role, &$invitedUser, &$temporaryPassword, &$token) {
            // Generate a random temporary password
            $temporaryPassword = Str::random(10);

       // Create the new user with the temporary password
      $invitedUser = User::create([
                'name' => explode('@', $email)[0],  // Use the part before '@' as the name
                'email' => $email,
                'password' => Hash::make($temporaryPassword),  // Store hashed password
            ]);

            // Set the current team ID for the user
            $invitedUser->current_team_id = $team->id;
            $invitedUser->save();

            // Attach the user to the team with the specified role
            $invitedUser->teams()->attach($team->id, ['role' => $role]);

            // Generate a password reset token
            $token = Password::createToken($invitedUser);

            // Create the team invitation (optional)
            $invitation = $team->teamInvitations()->create([
                'email' => $invitedUser->email,
                'role' => $role,  // Pass the role
            ]);
        });
	$invitation = \Laravel\Jetstream\TeamInvitation::where('email', $invitedUser->email)->first();
   
        
		
	
	// Send the invitation email
        Mail::to($invitedUser->email)->send(new TeamInvitation($invitation, $invitedUser, $token, $temporaryPassword));
    } else {
        // If the user exists, add them to the team and send them the reset password link
        $this->addUserToTeamAndSendResetLink($invitedUser, $team, $role);
    }
}	

    /**
     * Adds an existing user to the team and sends them a password reset link.
     */
     protected function addUserToTeamAndSendResetLink(User $invitedUser, Team $team, ?string $role)
{
    // Transaction to handle attaching user to team and updating the current team
    DB::transaction(function () use ($invitedUser, $team, $role) {
        // Attach the user to the team if they're not already attached
        if (!$invitedUser->teams->contains($team->id)) {
            $invitedUser->teams()->attach($team->id, ['role' => $role]);
        }

        // Set the current team ID for the user
        $invitedUser->current_team_id = $team->id;
        $invitedUser->save();
    });

    // Generate a password reset token
    $token = Password::createToken($invitedUser);

    // Generate a temporary password for the existing user (optional step, based on your logic)
    $temporaryPassword = Str::random(10);

    // Create the team invitation (Jetstream's TeamInvitation model)
    $invitation = $team->teamInvitations()->create([
        'email' => $invitedUser->email,
        'role' => $role,  // Pass the role
    ]);

    // Send the password reset email with the invitation, token, and temporary password
    Mail::to($invitedUser->email)->send(new TeamInvitation($invitation, $invitedUser, $token, $temporaryPassword));
}
 



	/**
     * Validate the invite member operation.
     */
    protected function validate(Team $team, string $email, ?string $role): void
    {
        Validator::make([
            'email' => $email,
            'role' => $role,
        ], $this->rules($team), [
            'email.unique' => __('This user has already been invited to the team.'),
        ])->after(
            $this->ensureUserIsNotAlreadyOnTeam($team, $email)
        )->validateWithBag('addTeamMember');
    }

    /**
     * Get the validation rules for inviting a team member.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    protected function rules(Team $team): array
    {
        return array_filter([
            'email' => [
                'required', 'email',
                Rule::unique(Jetstream::teamInvitationModel())->where(function (Builder $query) use ($team) {
                    $query->where('team_id', $team->id);
                }),
            ],
            'role' => Jetstream::hasRoles()
                            ? ['required', 'string', new Role]
                            : null,
        ]);
    }

    /**
     * Ensure that the user is not already on the team.
     */
    protected function ensureUserIsNotAlreadyOnTeam(Team $team, string $email): Closure
    {
        return function ($validator) use ($team, $email) {
            $validator->errors()->addIf(
                $team->hasUserWithEmail($email),
                'email',
                __('This user already belongs to the team.')
            );
        };
    }
}
