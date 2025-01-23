<?php

namespace App\Jobs;

use Carbon\Carbon;
use App\Models\Booking;
use App\Models\Setting;
use App\Models\OwnerData;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use App\Mail\BookingStatusNotification;
use Illuminate\Queue\InteractsWithQueue;
use App\Mail\ExpiredBookingsNotification;
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
        $expireTime = getSetting('booking_pending_expire_time');
        Log::info('[ExpirePendingBookings] Booking pending expire time is set to: ' . ($expireTime ?? 'N/A') . ' Hour');
        if (!$expireTime) {
            Log::warning('[ExpirePendingBookings] Booking pending expire time is not set. Using default value of 1 hour.');
            $expireTime = 1;
        }

        $expiredBookings = Booking::where('status', 'pending')
            ->where('created_at', '<', Carbon::now()->subHours($expireTime))
            ->get();

        Log::info('[ExpirePendingBookings] Server time: ' . Carbon::now());

        if ($expiredBookings->isEmpty()) {
            Log::info('[ExpirePendingBookings] No pending bookings to expire.');
            return;
        }

        $adminData = OwnerData::whereNotNull('email')->first();
        Log::info('[ExpirePendingBookings] Found ' . $expiredBookings->count() . ' pending bookings to expire, sending notification to: ' . ($adminData->email ?? 'N/A'));

        if ($adminData) {
            sendEmail(
                $adminData->email, // Destinatario
                new ExpiredBookingsNotification($expiredBookings), // Mailable
                'Errore nell\'invio dell\'email di notifica scadenza prenotazioni', // Messaggio di errore
                'it' // Locale per l'amministratore
            );
        } else {
            Log::warning('[ExpirePendingBookings] No administrator email found. Skipping admin notification.');
        }

        $notification = (bool) getSetting('booking_rejected_notification');
        Log::info('[ExpirePendingBookings] Booking rejected notification is ' . ($notification ? 'enabled' : 'disabled'));

        foreach ($expiredBookings as $booking) {
            try {
                $booking->update(['status' => 'rejected']);

                if (!$notification) {
                    Log::info('[ExpirePendingBookings] Booking ID {$booking->id} marked as rejected. Notification disabled.');
                    continue;
                } else {
                    Log::info('[ExpirePendingBookings] Booking ID {$booking->id} marked as rejected. Sending notification.');

                    // Usa la funzione sendEmail per inviare la mail
                    sendEmail(
                        $booking->email, // Destinatario
                        new BookingStatusNotification($booking), // Mailable
                        'Errore nell\'invio della notifica di prenotazione rifiutata', // Messaggio di errore
                        $booking->locale // Locale della prenotazione
                    );
                }

                Log::info('[ExpirePendingBookings] Booking ID {$booking->id} marked as rejected and notification sent.');
            } catch (\Exception $e) {
                Log::error('[ExpirePendingBookings] Failed to process booking ID {$booking->id}: ' . $e->getMessage());
            }
        }
    }
}
