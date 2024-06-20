<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Excursion extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price_increment',
        'price',
        'abstract',
        'description'
    ];

    public function images()
    {
        return $this->hasMany(Image::class);
    }
}
