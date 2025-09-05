<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Visit extends Model
{
    use HasFactory;

    protected $fillable = [
        'ip_address',
        'user_agent',
        'visited_at',
        'url',
    ];

    protected $casts = [
        'visited_at' => 'datetime',
    ];

    /**
     * Scope a query to only include visits from a specific date range.
     */
    public function scopeDateRange(Builder $query, $startDate, $endDate = null): Builder
    {
        $endDate = $endDate ?: now();
        
        return $query->whereBetween('visited_at', [
            Carbon::parse($startDate)->startOfDay(),
            Carbon::parse($endDate)->endOfDay(),
        ]);
    }

    /**
     * Scope a query to only include unique visits (by IP).
     */
    public function scopeUniqueVisitors(Builder $query): Builder
    {
        return $query->select('ip_address')
            ->selectRaw('MIN(visited_at) as first_visit')
            ->selectRaw('MAX(visited_at) as last_visit')
            ->selectRaw('COUNT(*) as total_visits')
            ->groupBy('ip_address');
    }

    /**
     * Get the total number of visits.
     */
    public static function getTotalVisits(): int
    {
        return static::count();
    }

    /**
     * Get the number of unique visitors.
     */
    public static function getUniqueVisitorsCount(): int
    {
        return static::distinct('ip_address')->count('ip_address');
    }
}
