<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'show',
    ];

    public function routes()
    {
        return $this->hasMany(Route::class, 'departure_id');
    }
}
