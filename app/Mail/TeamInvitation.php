<?php

namespace App\Mail;

use App\Actions\JetSteam\InviteTeamMember;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Laravel\Jetstream\TeamInvitation as JetstreamTeamInvitation;
use App\Models\User; 

class TeamInvitation extends Mailable 
{
    use Queueable, SerializesModels;

    public $invitation;
    public $user; 
    public $token;
    public $temporaryPassword;    

    
	/**
     * Create a new message instance.
     *
     * @param JetstreamTeamInvitation $invitation
     * @param string|null $token
     * @param string|null $temporaryPassword
     */
    public function __construct(JetstreamTeamInvitation $invitation, User $user, $token, $temporaryPassword = null)
    {
        $this->invitation = $invitation;
        $this->user = $user;
        $this->token = $token;
	$this->temporaryPassword = $temporaryPassword;


    }

    /**
     * Build the message.
     */
    public function build()
    {
	
	    return $this->subject('You have been invited to join a team')
                    ->view('emails.team-invitation')
                    ->with([
                        'invitation' => $this->invitation,
                        'user' => $this->user,  // Pass the user object to the view
                        'token' => $this->token,
                        'temporaryPassword' => $this->temporaryPassword,
                    ]);
    }



    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
   {
        return new Envelope(
            subject: 'Team Invitation',
        );
    }

    /**
     * Get the message content definition.
     
    public function content(): Content
    {
        return new Content(
            view: 'emails.team-invitation',
        );
    }
     */
    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
