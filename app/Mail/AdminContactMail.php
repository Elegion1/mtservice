<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;

class AdminContactMail extends Mailable
{
    use Queueable, SerializesModels;

    public $contatto;
    public $senderEmail;

    /**
     * Create a new message instance.
     */
    public function __construct($contatto, $senderEmail)
    {
        $this->contatto = $contatto;
        $this->senderEmail = $senderEmail;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            replyTo: [
                new Address($this->senderEmail, $this->contatto->nome . ' ' . $this->contatto->cognome),
            ],
            subject: 'Nuovo messaggio ricevuto',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.contattaci-admin',
        );
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
