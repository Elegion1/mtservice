<?php

namespace Database\Seeders;

use App\Models\Destination;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DestinationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $destinations = [
            "Aeroporto Trapani Birgi V.Florio",
            "Aeroporto Catania Fontanarossa",
            "Aeroporto Palermo Punta Raisi",
            "Palermo città",
            "Trapani città, porto",
            "Marsala",
            "San Vito Lo Capo"
        ];

        foreach ($destinations as $destination) {
            Destination::create([
                'name' => $destination
            ]);
        }
    }
}
