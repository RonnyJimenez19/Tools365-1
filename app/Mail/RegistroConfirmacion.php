<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RegistroConfirmacion extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '¡Bienvenido a Tools365! Confirma tu registro',
        );
    }

public function content(): Content
{
    return new Content(
        view: 'emails.registro-confirmacion',
        with: [
            'nombre'      => $this->user->name,
            'loginUrl'    => route('login'),
            'verifyUrl'   => route('email.verify', $this->user->verification_token),
        ],
    );
}
}