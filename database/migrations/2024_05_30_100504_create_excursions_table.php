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
            $table->string('abstract');
            $table->text('description');
            $table->timestamps();
        });

        $excursions = [
            [
                "name" => "Segesta",
                "price_increment" => 5,
                "price" => 120,
                "abstract" => "Visita l'antico sito archeologico di Segesta.",
                "description" => "Scopri la storia e la bellezza di Segesta, un'antica città siciliana con un tempio greco ben conservato e un teatro che offre viste mozzafiato."
            ],
            [
                "name" => "Marsala",
                "price_increment" => 5,
                "price" => 150,
                "abstract" => "Esplora la storica città di Marsala.",
                "description" => "Passeggia tra le vie storiche di Marsala, famosa per il suo vino e le sue saline. Goditi le viste panoramiche e la ricca cultura della città."
            ],
            [
                "name" => "Mazara Del Vallo",
                "price_increment" => 5,
                "price" => 230,
                "abstract" => "Scopri la bellezza di Mazara Del Vallo.",
                "description" => "Esplora Mazara Del Vallo, una città costiera nota per il suo centro storico, la Casbah e la vibrante cultura siciliana."
            ],
            [
                "name" => "Erice, Segesta",
                "price_increment" => 5,
                "price" => 220,
                "abstract" => "Tour combinato di Erice e Segesta.",
                "description" => "Visita la città medievale di Erice e l'antico sito archeologico di Segesta. Ammira il tempio greco, il teatro e le vedute panoramiche."
            ],
            [
                "name" => "Palermo Monreale",
                "price_increment" => 5,
                "price" => 300,
                "abstract" => "Esplora Palermo e Monreale.",
                "description" => "Scopri la capitale siciliana Palermo con i suoi mercati vivaci, chiese storiche e palazzi. Visita Monreale per ammirare il suo famoso duomo."
            ],
            [
                "name" => "Mothia saline, Marsala, Mazara Del Vallo",
                "price_increment" => 5,
                "price" => 290,
                "abstract" => "Tour delle saline di Mothia e delle città di Marsala e Mazara Del Vallo.",
                "description" => "Goditi una giornata esplorando le saline di Mothia, la storica città di Marsala e la pittoresca Mazara Del Vallo con la sua Casbah."
            ],
            [
                "name" => "Selinunte",
                "price_increment" => 5,
                "price" => 250,
                "abstract" => "Visita il parco archeologico di Selinunte.",
                "description" => "Esplora uno dei più grandi parchi archeologici d'Europa, Selinunte, con i suoi templi greci e rovine affascinanti situati sulla costa siciliana."
            ],
            [
                "name" => "Grotte Mangiapane e Riserva Naturale Orientata Monte Cofano",
                "price_increment" => 5,
                "price" => 170,
                "abstract" => "Esplora le grotte Mangiapane e la Riserva Naturale di Monte Cofano.",
                "description" => "Scopri le grotte Mangiapane, abitate fin dal Paleolitico, e la Riserva Naturale di Monte Cofano con le sue spettacolari formazioni rocciose e la fauna selvatica."
            ],
            [
                "name" => "Marsala città, Saline dello stagnone Ettore Infersa e isola di Mothia",
                "price_increment" => 5,
                "price" => 220,
                "abstract" => "Tour della città di Marsala, delle saline e dell'isola di Mothia.",
                "description" => "Esplora Marsala, famosa per il vino e le saline dello Stagnone. Visita l'isola di Mothia con i suoi resti archeologici fenici."
            ],
            [
                "name" => "Saline museo del sale Trapani",
                "price_increment" => 5,
                "price" => 160,
                "abstract" => "Visita il museo del sale di Trapani.",
                "description" => "Scopri la tradizione della raccolta del sale a Trapani visitando il museo del sale, situato nelle storiche saline della città."
            ]
        ];

        foreach ($excursions as $excursion) {
            Excursion::create($excursion);
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
