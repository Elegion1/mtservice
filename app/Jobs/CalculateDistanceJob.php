<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class CalculateDistanceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $origin;
    protected $destination;
    protected $cacheKey;
    protected $key;

    /**
     * Create a new job instance.
     */
    public function __construct($origin, $destination, $cacheKey, $key)
    {
        $this->origin = $origin;
        $this->destination = $destination;
        $this->cacheKey = $cacheKey;
        $this->key = $key;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $distance = $this->getDistanceFromAPI();
            
            if ($distance !== null) {
                // Salva il risultato in cache per 5 minuti
                Cache::put($this->cacheKey, [
                    'distance' => $distance,
                    'key' => $this->key,
                    'status' => 'completed',
                ], now()->addMinutes(5));
                
                Log::info('Distance calculation completed and cached for ' . $this->cacheKey . ': ' . $distance . ' km');
            } else {
                // Salva l'errore in cache
                Cache::put($this->cacheKey, [
                    'status' => 'error',
                    'message' => 'Failed to calculate distance',
                ], now()->addMinutes(5));
                Log::warning('Distance calculation failed for ' . $this->cacheKey);
            }
        } catch (\Exception $e) {
            Log::error('Error in CalculateDistanceJob: ' . $e->getMessage());
            
            // Salva l'errore in cache
            Cache::put($this->cacheKey, [
                'status' => 'error',
                'message' => $e->getMessage(),
            ], now()->addMinutes(5));
        }
    }

    private function getDistanceFromAPI()
    {
        $apiKey = env('GOOGLE_MAPS_API_KEY');
        $origin = urlencode($this->origin);
        $destination = urlencode($this->destination);

        $url = "https://maps.googleapis.com/maps/api/distancematrix/json?destinations={$destination}&origins={$origin}&key={$apiKey}";

        try {
            $response = @file_get_contents($url);
            Log::info('Google Maps API Response: ' . $response);
            $data = json_decode($response, true);

            if ($data['status'] === 'OK') {
                if (isset($data['destination_addresses'][0]) && isset($data['rows'][0]['elements'][0]['distance']['value'])) {
                    return $data['rows'][0]['elements'][0]['distance']['value'] / 1000; // Convert to KM
                } else {
                    Log::error('Destination not found or incorrect response format');
                    return null;
                }
            } else {
                Log::error('Google Maps API Error: ' . $data['status']);
                return null;
            }
        } catch (\Exception $e) {
            Log::error('Exception in getDistanceFromAPI: ' . $e->getMessage());
            return null;
        }
    }
}
