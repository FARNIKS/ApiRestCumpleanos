<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;


class DataQualityMail extends Mailable
{
    use Queueable, SerializesModels;

    public $auditRecords;

    public function __construct(Collection $auditRecords)
    {
        $this->auditRecords = $auditRecords;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Registros para revisar fecha de nacimiento - OBGROUP',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.audit',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
