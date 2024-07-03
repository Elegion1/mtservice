<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\CssSelector\XPath\Extension\FunctionExtension;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'path',
        'service_id',
        'excursion_id',
        'partner_id',
        'owner_data_id',
        'content_id',
        'car_id'
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function excursion()
    {
        return $this->belongsTo(Excursion::class);
    }

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    public function content()
    {
        return $this->belongsTo(Content::class);
    }

    public function ownerData()
    {
        return $this->belongsTo(OwnerData::class);
    }

    public function car()
    {
        return $this->belongsTo(Car::class);
    }
    


}
