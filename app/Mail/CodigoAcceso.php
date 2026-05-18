<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CodigoAcceso extends Mailable
{
    public function __construct(
        public string $codigo,
        public string $nombre
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: '🔐 Tu código de verificación — Ubicatec');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.codigo-acceso');
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
