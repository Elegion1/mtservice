<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'title_it',
        'title_en',
        'subtitle_it',
        'subtitle_en',
        'subtitleSec_it',
        'subtitleSec_en',
        'abstract_it',
        'abstract_en',
        'body_it',
        'body_en',
        'links',
        'condition_it',
        'condition_en',
        'flag',
        'show'
    ];

    public function images()
    {
        return $this->hasMany(Image::class);
    }
}
