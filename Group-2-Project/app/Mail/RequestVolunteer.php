<?php

namespace App\Mail;

use App\Models\Schedule;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RequestVolunteer extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Schedule $schedule,
        public string $customMessage = '',
        public int $needVolunteers = 1
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Claddagh Watch: Volunteer Request',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.request-volunteer',
            with: [
                'patrolNr' => $this->schedule->patrolNr,
                'patrolDate' => $this->schedule->patrolDate,
                'patrolDescription' => $this->schedule->patrolDescription,
                'customMessage' => $this->customMessage,
                'needVolunteers' => $this->needVolunteers,
            ]
        );
    }
}
