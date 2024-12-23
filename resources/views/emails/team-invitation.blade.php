<!-- resources/views/emails/team-invitation.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Team Invitation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f7;
            color: #51545e;
            margin: 0;
            padding: 0;
        }
        .email-wrapper {
            width: 100%;
            background-color: #f4f4f7;
            padding: 20px;
        }
        .email-content {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 3px rgba(0, 0, 0, 0.1);
        }
        .button {
            background-color: #3869d4;
            color: #ffffff;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
        }
        .button:hover {
            background-color: #3151b7;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 12px;
            color: #6b6e76;
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-content">
            <h1>Hello {{ $user->name }},</h1>

            <p>You have been invited to join the team. Please use the following link to set a new password.</p>

            @if($temporaryPassword)
                <p>Your temporary password is: <strong>{{ $temporaryPassword }}</strong></p>
            @endif

            <p>To set a new password, click the button below:</p>

            <p>
                <a href="{{ route('password.reset', ['token' => $token, 'email' => $user->email]) }}" class="button">
                    Reset Password
                </a>
            </p>

            <p>If you did not expect to receive this invitation, you may ignore this email.</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Your Company. All rights reserved.</p>
        </div>
    </div>
</body>
</html>

