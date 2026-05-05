<?php

namespace App\Mail;

use App\Models\NoBirthdayConfig; // IMPORTANTE
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NoBirthdaysMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $config; // Declaramos la variable pública

    public function __construct(array $data)
    {
        $this->data = $data;
        $this->config = NoBirthdayConfig::first();
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Hoy nos preparamos para grandes retos - OBGROUP',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.no-birthday',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
