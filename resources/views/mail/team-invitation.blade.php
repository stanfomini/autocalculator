@component('mail::message')

# Hello {{ $user->name }},

You have been invited to join the team.

@if($temporaryPassword)
    <p>Your temporary password is: <strong>{{ $temporaryPassword }}</strong></p>
@endif

<p>To set a new password, please click the following link:</p>

@component('mail::button', ['url' => route('password.reset', ['token' => $token, 'email' => $user->email])])
Reset Password
@endcomponent

@endcomponent

