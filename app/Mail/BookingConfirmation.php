<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\App;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;

class BookingConfirmation extends Mailable
{
    use Queueable, SerializesModels;
    public $booking;
    public $pdf;

    /**
     * Create a new message instance.
     *
     * @param string $pdf
     */
    public function __construct($booking, $pdf)
    {
        $this->pdf = $pdf;
        $this->booking = $booking;
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
            with: ['booking' => $this->booking, 'locale' => App::getLocale()],
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
