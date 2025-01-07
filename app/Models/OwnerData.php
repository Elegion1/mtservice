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
        'phoneName',
        'phone2Name',
        'phone3Name',
        'phone',
        'phone2',
        'phone3',
        'email',
        'whatsappLink',
        'siteName',
        'facebook',
    ];

    public function images()
    {
        return $this->hasMany(Image::class);
    }
}
