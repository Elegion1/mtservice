<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function departure()
    {
        return $this->belongsTo(Destination::class, 'departure_id');
    }

    public function arrival()
    {
        return $this->belongsTo(Destination::class, 'arrival_id');
    }
}
