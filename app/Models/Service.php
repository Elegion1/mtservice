<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subtitle',
        'subtitleSec',
        'abstract',
        'body',
        'links',
        'condition',
    ];

    public function images()
    {
        return $this->hasMany(Image::class);
    }
}
