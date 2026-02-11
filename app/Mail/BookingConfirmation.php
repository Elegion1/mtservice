<?php

namespace App\Mail;

use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class BookingConfirmation extends BaseBookingMailable
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
        return new Envelope(
            subject: __('ui.bookingSummary'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.booking-confirmation',
            with: ['booking' => $this->booking, 'locale' => $this->booking->locale],
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
