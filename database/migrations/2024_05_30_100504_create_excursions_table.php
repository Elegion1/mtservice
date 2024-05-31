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
            $table->string('price_increment');
            $table->string('price');
            $table->timestamps();
        });

        $excursions = [
            ["name" => "Segesta", "price_increment" => 5, "price" => 120],
            ["name" => "Marsala", "price_increment" => 5, "price" => 150],
            ["name" => "Mazara Del Vallo", "price_increment" => 5, "price" => 230],
            ["name" => "Erice, Segesta", "price_increment" => 5, "price" => 220],
            ["name" => "Palermo Monreale", "price_increment" => 5, "price" => 300],
            ["name" => "Mothia saline, Marsala, Mazara Del Vallo", "price_increment" => 5, "price" => 290],
            ["name" => "Selinunte", "price_increment" => 5, "price" => 250],
            ["name" => "Grotte Mangiapane e Riserva Naturale Orientata Monte Cofano", "price_increment" => 5, "price" => 170],
            ["name" => "Marsala citta, Saline dello stagnone Ettore Infersa e isola di Mothia", "price_increment" => 5, "price" => 220],
            ["name" => "Saline museo del sale Trapani", "price_increment" => 5, "price" => 70],
            ["name" => "Riserva Zingaro, Scopello, Faraglioni, Guidaloca", "price_increment" => 5, "price" => 230],
            ["name" => "Erice, Saline di Trapani", "price_increment" => 5, "price" => 150],
            ["name" => "Wine Tour Marsala", "price_increment" => 5, "price" => 170],
            ["name" => "Valle dei Templi, Scala dei Turchi Agrigento", "price_increment" => 5, "price" => 600],
            ["name" => "Scopello, Segesta", "price_increment" => 5, "price" => 200],
            ["name" => "San Vito Lo Capo", "price_increment" => 5, "price" => 190],
            ["name" => "Erice", "price_increment" => 5, "price" => 90],
        ];
        
        foreach($excursions as $excursion) {
            Excursion::create([
                'name' => $excursion['name'],
                'price_increment' => $excursion['price_increment'],
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
