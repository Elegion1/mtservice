<?php

namespace App\Mail;

use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class BookingAdmin extends BaseBookingMailable
{
    /**
     * Create a new message instance.
     */
    public function __construct($booking, $pdf)
    {
        parent::__construct($booking, $pdf);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = 'Nuova prenotazione disponibile';
        
        if (!empty($this->booking->bookingData['sito_favignana'])) {
            $subject = 'Sito Favignana, nuova prenotazione disponibile';
        }

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.booking-admin',
            with: ['booking' => $this->booking],
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return $this->attachmentFromPdf();
    }
}
