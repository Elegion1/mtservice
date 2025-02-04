<?php

namespace App\Models;

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
        'bookingData', // Assicurati che questo campo sia incluso negli attributi fillable
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'bookingData' => 'array', // Casta il campo bookingData come array
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
}
