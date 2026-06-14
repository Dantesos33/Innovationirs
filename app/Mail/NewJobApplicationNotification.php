<?php
namespace App\Mail;

use App\Models\JobApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewJobApplicationNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly JobApplication $application
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[Application] ' . $this->application->full_name
            . ' — ' . $this->application->careerPosting->title
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.job-application-notification',
            with: ['application' => $this->application],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
