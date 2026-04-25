<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserCreated extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public User $user,
        public string $password
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'CladdaghWatch Account Created',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.user-created',
            with: [
                'firstName' => $this->user->FirstName,
                'lastName' => $this->user->LastName,
                'email' => $this->user->email,
                'password' => $this->password,
                'userType' => $this->getUserTypeLabel($this->user->userTypeNr),
            ]
        );
    }

    /**
     * Get the user type label for display.
     */
    private function getUserTypeLabel($userTypeNr): string
    {
        return match((int)$userTypeNr) {
            1 => 'Administrator',
            2 => 'Manager',
            3 => 'Volunteer',
            4 => 'Supervisor',
            default => 'User',
        };
    }
}
