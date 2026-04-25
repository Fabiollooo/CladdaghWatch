<?php

namespace App\Mail;

use App\Models\Schedule;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/* Mail CLass which defines what the email looks like */

class VolunteerConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public User $volunteer,
        public Schedule $schedule
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Patrol Volunteer Confirmation',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.volunteer-confirmation',
            with: [
                'volunteerName' => $this->volunteer->FirstName . ' ' . $this->volunteer->LastName,
                'patrolDate' => $this->schedule->patrolDate,
                'patrolDescription' => $this->schedule->patrolDescription,
            ]
        );
    }


    public function attachments(): array
    {
        return [];
    }
}
