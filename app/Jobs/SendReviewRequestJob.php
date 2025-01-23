<?php

namespace App\Jobs;

use App\Mail\ReviewRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendReviewRequestJob implements ShouldQueue
{
    use Queueable;

    public $booking;
    /**
     * Create a new job instance.
     */
    public function __construct($booking)
    {
        $this->booking = $booking;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($this->booking->status != 'confirmed') {
            Log::warning('[SendReviewRequestJob] Prenotazione non confermata. Job interrotto. ID Prenotazione: ' . $this->booking->id);
            return;
        }

        try {
            Mail::to($this->booking->email)->send(new ReviewRequest($this->booking));
            Log::info('[SendReviewRequestJob] Inviata richiesta di recensione per la prenotazione: ' . $this->booking->id . ' Lingua: ' . $this->booking->locale);
        } catch (\Exception $e) {
            Log::error('[SendReviewRequestJob] Errore durante l\'invio della mail: ' . $e->getMessage());
        }
    }
}
