<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subtitle',
        'body',
        'links',
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
