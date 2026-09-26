<?php

namespace App\Mail;

use App\Models\Solicitud;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SolicitudEstadoCambiado extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Solicitud $solicitud) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Actualización de tu solicitud {$this->solicitud->codigo}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.solicitud-estado',
        );
    }
}
