<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'price',
        'show',
        'kasko',
        'kasko_price',
    ];

    /**
     * Scope query to only return visible cars
     */
    public function scopeVisible($query)
    {
        return $query->where('show', 1);
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function carPrices()
    {
        return $this->hasMany(CarPrice::class);
    }

}
