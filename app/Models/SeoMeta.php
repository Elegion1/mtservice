<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeoMeta extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_key', 'title', 'description',
    ];

    protected static function booted()
    {
        static::saved(function () {
            cache()->forget('seo_map_data');
        });

        static::deleted(function () {
            cache()->forget('seo_map_data');
        });
    }
}
