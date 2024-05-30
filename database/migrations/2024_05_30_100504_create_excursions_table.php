<?php

use App\Models\Excursion;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('excursions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('price');
            $table->timestamps();
        });

        $excursions = [
            ["name" => "Segesta", "price" => 120],
            ["name" => "Marsala", "price" => 150],
            ["name" => "Mazara Del Vallo", "price" => 230],
            ["name" => "Erice, Segesta", "price" => 220],
            ["name" => "Palermo Monreale", "price" => 300],
            ["name" => "Mothia saline, Marsala, Mazara Del Vallo", "price" => 290],
            ["name" => "Selinunte", "price" => 250],
            ["name" => "Grotte Mangiapane e Riserva Naturale Orientata Monte Cofano", "price" => 170],
            ["name" => "Marsala citta, Saline dello stagnone Ettore Infersa e isola di Mothia", "price" => 220],
            ["name" => "Saline museo del sale Trapani", "price" => 70],
            ["name" => "Riserva Zingaro, Scopello, Faraglioni, Guidaloca", "price" => 230],
            ["name" => "Erice, Saline di Trapani", "price" => 150],
            ["name" => "Wine Tour Marsala", "price" => 170],
            ["name" => "Valle dei Templi, Scala dei Turchi Agrigento", "price" => 600],
            ["name" => "Scopello, Segesta", "price" => 200],
            ["name" => "San Vito Lo Capo", "price" => 190],
            ["name" => "Erice", "price" => 90],
        ];
        
        foreach($excursions as $excursion) {
            Excursion::create([
                'name' => $excursion['name'],
                'price' => $excursion['price'],
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('excursions');
    }
};
