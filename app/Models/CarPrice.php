<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'car_id',
        'time_period_id',
        'price',
    ];

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function timePeriod()
    {
        return $this->belongsTo(TimePeriod::class);
    }

    // Accessor per 'start'
    // public function getStartAttribute()
    // {
    //     return $this->timePeriod ? $this->timePeriod->start : null;
    // }

    // // Accessor per 'end'
    // public function getEndAttribute()
    // {
    //     return $this->timePeriod ? $this->timePeriod->end : null;
    // }

    // public function getIdAttribute()
    // {
    //     return $this->timePeriod ? $this->timePeriod->id : null;
    // }
}
