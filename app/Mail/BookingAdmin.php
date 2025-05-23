<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Contracts\Queue\ShouldQueue;

class BookingAdmin extends Mailable
{
    use Queueable, SerializesModels;
    public $booking;
    public $pdf;
    /**
     * Create a new message instance.
     */
    public function __construct($booking, $pdf)
    {
        $this->pdf = $pdf;
        $this->booking = $booking;

        if (!empty($this->booking->bookingData['sito_favignana'])) {
            $this->subject = 'Sito Favignana, nuova prenotazione disponibile';
        } else {
            $this->subject = 'Nuova prenotazione disponibile';
        }
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subject,
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
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $filename = 'booking_' . now()->format('YmdHis') . '.pdf';
        return [
            Attachment::fromData(fn() => $this->pdf, $filename)->withMime('application/pdf')
        ];
    }
}
