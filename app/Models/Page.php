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

    public function contents()
    {
        return $this->hasMany(Content::class);
    }

}
