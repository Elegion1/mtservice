<?php

namespace App\Jobs;

use Carbon\Carbon;
use App\Models\Booking;
use App\Models\Setting;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use App\Mail\BookingStatusNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ExpirePendingBookings implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $expireTime = Setting::where('name', 'booking_pending_expire_time')->value('value');
        // Trova tutte le prenotazioni in stato "pending" più vecchie di 24 ore
        $expiredBookings = Booking::where('status', 'pending')
            ->where('created_at', '<', Carbon::now()->subHours($expireTime ?? 24))
            ->get();

        // Aggiorna lo stato di ciascuna prenotazione a "rifiutata"
        foreach ($expiredBookings as $booking) {
            $booking->update(['status' => 'rejected']);
            Mail::to($booking->email)->send(new BookingStatusNotification($booking));
            Log::info("Booking ID {$booking->id} marked as rejected.");
        }
    }
}
