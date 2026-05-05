<?php

namespace App\Mail;

use App\Models\BirthdayConfig; // IMPORTANTE
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BirthdayMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $config;

    public function __construct(array $data)
    {
        $this->data = $data;
        $this->config = BirthdayConfig::first();
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Celebraciones de Cumpleaños - OBGROUP',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.birthday',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
