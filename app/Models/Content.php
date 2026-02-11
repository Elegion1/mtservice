<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    use HasFactory;

    protected $fillable = [
        'title_it',
        'title_en',
        'subtitle_it',
        'subtitle_en',
        'body_it',
        'body_en',
        'links',
        'order',
        'show',
        'page_id',
        'start_date',
        'end_date'
    ];

    /**
     * Scope query to only return visible content
     */
    public function scopeVisible($query)
    {
        return $query->where('show', 1);
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function page()
    {
        return $this->belongsTo(Page::class);
    }


}
