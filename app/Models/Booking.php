<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'surname',
        'email',
        'dial_code',
        'phone',
        'body',
        'status',
        'payment_status',
        'code',
        'locale',
        'service_date',
        'bookingData',
        'info',
        'hidden_in_calendar',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'bookingData' => 'array', // Casta il campo bookingData come array
        'info' => 'array', // Casta il campo info come array
        'hidden_in_calendar' => 'boolean',
    ];

    public function getStartDateAttribute()
    {
        $details = $this->bookingData;

        switch ($details['type']) {
            case 'transfer':
                return $details['date_dep'];
            case 'noleggio':
                return $details['date_start'];
            case 'escursione':
                return $details['date_dep'];
            default:
                return null;
        }
    }

    public function getRejectedCalendarVisibilityDeadline(): ?Carbon
    {
        $bookingData = $this->bookingData ?? [];
        $type = $bookingData['type'] ?? null;

        $referenceDate = null;

        if ($type === 'transfer') {
            $referenceDate = !empty($bookingData['date_ret']) ? $bookingData['date_ret'] : ($bookingData['date_dep'] ?? null);
        } elseif ($type === 'escursione') {
            $referenceDate = $bookingData['date_dep'] ?? null;
        } elseif ($type === 'noleggio') {
            $referenceDate = $bookingData['date_end'] ?? null;
        } else {
            $referenceDate = $bookingData['date_dep'] ?? $bookingData['date_start'] ?? null;
        }

        if (empty($referenceDate)) {
            return null;
        }

        try {
            return Carbon::parse($referenceDate)->addWeek()->endOfDay();
        } catch (\Throwable $e) {
            return null;
        }
    }

    public function isAutomaticallyHiddenFromCalendar(): bool
    {
        if ($this->status !== 'rejected') {
            return false;
        }

        $visibilityDeadline = $this->getRejectedCalendarVisibilityDeadline();

        if (!$visibilityDeadline) {
            return false;
        }

        return Carbon::now()->greaterThan($visibilityDeadline);
    }

    public function isHiddenFromCalendar(): bool
    {
        return (bool) $this->hidden_in_calendar || $this->isAutomaticallyHiddenFromCalendar();
    }
}
