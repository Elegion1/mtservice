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
        'slug',
        
    ];

    /**
     * Scope query to only return visible destinations
     */
    public function scopeVisible($query)
    {
        return $query->where('show', 1);
    }

    public function routes()
    {
        return $this->hasMany(Route::class, 'departure_id');
    }
}
