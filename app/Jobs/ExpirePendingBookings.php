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
        $expireTime = Setting::where('name', 'booking_pending_expire_time')->value('value');
        Log::info('Booking pending expire time is set to: ' . ($expireTime ?? 'N/A') . ' Hour');
        if (!$expireTime) {
            Log::warning('Booking pending expire time is not set. Using default value of 1 hour.');
            $expireTime = 1;
        }

        $expiredBookings = Booking::where('status', 'pending')
            ->where('created_at', '<', Carbon::now()->subHours($expireTime))
            ->get();

        Log::info('Server time: ' . Carbon::now());
        
        if ($expiredBookings->isEmpty()) {
            Log::info('No pending bookings to expire.');
            return;
        }

        $adminData = OwnerData::whereNotNull('email')->first();
        Log::info('Found ' . $expiredBookings->count() . ' pending bookings to expire, sending notification to: ' . ($adminData->email ?? 'N/A'));

        if ($adminData) {
            Mail::to($adminData->email)->send(new ExpiredBookingsNotification($expiredBookings));
        } else {
            Log::warning('No administrator email found. Skipping admin notification.');
        }

        $notification = (bool) Setting::where('name', 'booking_rejected_notification')->value('value');
        Log::info('Booking rejected notification is ' . ($notification ? 'enabled' : 'disabled'));

        foreach ($expiredBookings as $booking) {
            try {
                $booking->update(['status' => 'rejected']);
                if (!$notification) {
                    Log::info("Booking ID {$booking->id} marked as rejected. Notification disabled.");
                    continue;
                } else {
                    Log::info("Booking ID {$booking->id} marked as rejected. Sending notification.");
                    Mail::to($booking->email)->send(new BookingStatusNotification($booking));
                }

                Log::info("Booking ID {$booking->id} marked as rejected and notification sent.");
            } catch (\Exception $e) {
                Log::error("Failed to process booking ID {$booking->id}: " . $e->getMessage());
            }
        }
    }
}
