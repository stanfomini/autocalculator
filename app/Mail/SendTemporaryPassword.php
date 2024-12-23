<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendTemporaryPassword extends Mailable
{
    use Queueable, SerializesModels;


    public $user;
    public $temporaryPassword;
    /**
     * Create a new message instance.
     */
    public function __construct($temporaryPassword)
    {
    $this->user = $user;    
    $this->temporaryPassword = $temporaryPassword;
}

public function build()
{
	return $this->subject('Your Company Invited You!')
		->view('emails.team-invitation')
		->with([
		    'user' => $this->user,
                    'temporaryPassword' => $this->temporaryPassword,
                ]);
}
    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Send Temporary Password',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.send-temporary-password',
        );
    }

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
