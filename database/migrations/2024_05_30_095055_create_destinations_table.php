<?php

use App\Models\Destination;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('destinations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('destinations');
    }
};
