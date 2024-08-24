<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountPeriod extends Model
{
    use HasFactory;

    protected $fillable = [
        'start',
        'end',
        'discount_id',
    ];

    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }
}
