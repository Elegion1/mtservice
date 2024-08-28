<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_it',
        'name_en',
        'percentage',
        'applicable_to',
        'applies_to_transfer',
        'applies_to_rental',
        'applies_to_excursion',
    ];

    public function discount_periods()
    {
        return $this->hasMany(DiscountPeriod::class);
    }
}
