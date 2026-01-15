<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimePeriod extends Model
{
    use HasFactory;

    protected $fillable = [
        'start',
        'end',
        'discount_id',
    ];

    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }

    public function carPrices()
    {
        return $this->hasMany(CarPrice::class);
    }

    /**
     * Genera automaticamente il nome del periodo dalle date
     * Es: "1 Gennaio - 30 Aprile"
     */
    public function getFormattedNameAttribute()
    {
        if (!$this->start || !$this->end) {
            return 'Periodo sconosciuto';
        }

        $start = \Carbon\Carbon::parse($this->start);
        $end = \Carbon\Carbon::parse($this->end);

        return $start->translatedFormat('j F') . ' - ' . $end->translatedFormat('j F');
    }

    /**
     * Accessor per ottenere il nome formattato
     */
    protected $appends = ['formatted_name'];
}
