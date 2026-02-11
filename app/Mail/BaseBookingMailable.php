<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

abstract class BaseBookingMailable extends Mailable
{
    use Queueable, SerializesModels;

    protected $booking;
    protected $pdf;

    /**
     * Create a new message instance.
     */
    public function __construct($booking, $pdf = null)
    {
        $this->booking = $booking;
        $this->pdf = $pdf;
    }

    /**
     * Get PDF attachment for booking confirmation
     *
     * @return array
     */
    protected function attachmentFromPdf(): array
    {
        if (!$this->pdf) {
            return [];
        }

        $filename = 'booking_' . $this->booking->code . now()->format('YmdHis') . '.pdf';
        return [
            Attachment::fromData(fn() => $this->pdf, $filename)->withMime('application/pdf')
        ];
    }

    /**
     * Get the attachments for the message.
     * Override in subclasses if needed
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
