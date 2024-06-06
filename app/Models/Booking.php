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
        'phone',
        'body',
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
}
