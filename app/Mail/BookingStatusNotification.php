<?php

namespace App\Mail;

use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class BookingStatusNotification extends BaseBookingMailable
{
    /**
     * Create a new message instance.
     */
    public function __construct($booking)
    {
        parent::__construct($booking);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('ui.bookingStatusNotification'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.booking-status-notification',
            with: ['booking' => $this->booking],
        );
    }
}
