<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OwnerData extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'surname',
        'companyName',
        'address',
        'city',
        'pIva',
        'codFisc',
        'phone',
        'phone2',
        'phone3',
        'email'
    ];

    public function images()
    {
        return $this->hasMany(Image::class);
    }
}
