<?php

namespace App\Mail;

use App\Models\Solicitud;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NuevaSolicitudAdmin extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Solicitud $solicitud, public bool $reasignada = false) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: ($this->reasignada ? 'Solicitud asignada' : 'Nueva solicitud recibida').": {$this->solicitud->codigo}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.nueva-solicitud-admin',
        );
    }
}
