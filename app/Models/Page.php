<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'link',
        'order',
        'show'
    ];

    /**
     * Scope query to only return visible pages
     */
    public function scopeVisible($query)
    {
        return $query->where('show', 1);
    }

    public function contents()
    {
        return $this->hasMany(Content::class);
    }

}
