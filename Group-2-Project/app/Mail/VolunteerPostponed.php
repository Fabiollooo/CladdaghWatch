<?php

namespace App\Mail;

use App\Models\Schedule;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VolunteerPostponed extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $volunteer,
        public Schedule $schedule,
        public string $oldPatrolDate,
        public string $newPatrolDate
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Patrol Postponed Notice',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.volunteer-postponed',
            with: [
                'volunteerName' => trim(($this->volunteer->FirstName ?? '') . ' ' . ($this->volunteer->LastName ?? '')),
                'patrolDescription' => $this->schedule->patrolDescription,
                'oldPatrolDate' => $this->oldPatrolDate,
                'newPatrolDate' => $this->newPatrolDate,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}