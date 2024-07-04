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
        'page_id'
    ];

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function page()
    {
        return $this->belongsTo(Page::class);
    }


}
