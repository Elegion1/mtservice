<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Excursion extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_it',
        'name_en',
        'price_increment',
        'price',
        'abstract_it',
        'abstract_en',
        'description_it',
        'description_en',
        'duration',
        'show',
        'increment_passengers',
    ];

    public function images()
    {
        return $this->hasMany(Image::class);
    }
}
