<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    use HasFactory;

    protected $fillable = [
        'departure_id',
        'arrival_id',
        'price',
        'price_increment',
        'duration',
        'distance',
        'show',
        'increment_passengers',
    ];

    public function reverseRoute()
    {
        return self::where('departure_id', $this->arrival_id)
            ->where('arrival_id', $this->departure_id)
            ->first();
    }

    public function departure()
    {
        return $this->belongsTo(Destination::class, 'departure_id');
    }

    public function arrival()
    {
        return $this->belongsTo(Destination::class, 'arrival_id');
    }
}
