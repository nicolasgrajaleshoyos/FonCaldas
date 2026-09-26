<?php

namespace App\Mail;

use App\Models\Pqrs;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PqrsNotificacion extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Pqrs $pqrs, public string $asunto, public string $mensaje) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: $this->asunto);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.pqrs');
    }
}
