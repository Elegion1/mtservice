<?php

namespace Database\Seeders;

use App\Models\Excursion;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ExcursionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $excursions = [
            [
                "name_it" => "Segesta",
                "name_en" => "Segesta",
                "price_increment" => 5,
                "price" => 120,
                "abstract_it" => "Visita l'antico sito archeologico di Segesta.",
                "abstract_en" => "Visit the ancient archaeological site of Segesta.",
                "description_it" => "Scopri la storia e la bellezza di Segesta, un'antica città siciliana con un tempio greco ben conservato e un teatro che offre viste mozzafiato.",
                "description_en" => "Discover the history and beauty of Segesta, an ancient Sicilian city with a well-preserved Greek temple and a theater that offers breathtaking views.",
                "duration" => 1,
                'show' => 1
            ],
            [
                "name_it" => "Marsala",
                "name_en" => "Marsala",
                "price_increment" => 5,
                "price" => 150,
                "abstract_it" => "Esplora la storica città di Marsala.",
                "abstract_en" => "Explore the historic city of Marsala.",
                "description_it" => "Passeggia tra le vie storiche di Marsala, famosa per il suo vino e le sue saline. Goditi le viste panoramiche e la ricca cultura della città.",
                "description_en" => "Stroll through the historic streets of Marsala, famous for its wine and salt flats. Enjoy panoramic views and the city's rich culture.",
                "duration" => 1,
                'show' => 1
            ],
            [
                "name_it" => "Mazara Del Vallo",
                "name_en" => "Mazara Del Vallo",
                "price_increment" => 5,
                "price" => 230,
                "abstract_it" => "Scopri la bellezza di Mazara Del Vallo.",
                "abstract_en" => "Discover the beauty of Mazara Del Vallo.",
                "description_it" => "Esplora Mazara Del Vallo, una città costiera nota per il suo centro storico, la Casbah e la vibrante cultura siciliana.",
                "description_en" => "Explore Mazara Del Vallo, a coastal city known for its historic center, the Casbah, and vibrant Sicilian culture.",
                "duration" => 1,
                'show' => 1
            ],
            [
                "name_it" => "Erice, Segesta",
                "name_en" => "Erice, Segesta",
                "price_increment" => 5,
                "price" => 220,
                "abstract_it" => "Tour combinato di Erice e Segesta.",
                "abstract_en" => "Combined tour of Erice and Segesta.",
                "description_it" => "Visita la città medievale di Erice e l'antico sito archeologico di Segesta. Ammira il tempio greco, il teatro e le vedute panoramiche.",
                "description_en" => "Visit the medieval town of Erice and the ancient archaeological site of Segesta. Admire the Greek temple, theater, and panoramic views.",
                "duration" => 1,
                'show' => 1
            ],
            [
                "name_it" => "Palermo Monreale",
                "name_en" => "Palermo Monreale",
                "price_increment" => 5,
                "price" => 300,
                "abstract_it" => "Esplora Palermo e Monreale.",
                "abstract_en" => "Explore Palermo and Monreale.",
                "description_it" => "Scopri la capitale siciliana Palermo con i suoi mercati vivaci, chiese storiche e palazzi. Visita Monreale per ammirare il suo famoso duomo.",
                "description_en" => "Discover the Sicilian capital Palermo with its lively markets, historic churches, and palaces. Visit Monreale to admire its famous cathedral.",
                "duration" => 1,
                'show' => 1
            ],
            [
                "name_it" => "Mothia saline, Marsala, Mazara Del Vallo",
                "name_en" => "Mothia salt pans, Marsala, Mazara Del Vallo",
                "price_increment" => 5,
                "price" => 290,
                "abstract_it" => "Tour delle saline di Mothia e delle città di Marsala e Mazara Del Vallo.",
                "abstract_en" => "Tour of the Mothia salt pans and the cities of Marsala and Mazara Del Vallo.",
                "description_it" => "Goditi una giornata esplorando le saline di Mothia, la storica città di Marsala e la pittoresca Mazara Del Vallo con la sua Casbah.",
                "description_en" => "Enjoy a day exploring the Mothia salt pans, the historic city of Marsala, and the picturesque Mazara Del Vallo with its Casbah.",
                "duration" => 1,
                'show' => 1
            ],
            [
                "name_it" => "Selinunte",
                "name_en" => "Selinunte",
                "price_increment" => 5,
                "price" => 250,
                "abstract_it" => "Visita il parco archeologico di Selinunte.",
                "abstract_en" => "Visit the archaeological park of Selinunte.",
                "description_it" => "Esplora uno dei più grandi parchi archeologici d'Europa, Selinunte, con i suoi templi greci e rovine affascinanti situati sulla costa siciliana.",
                "description_en" => "Explore one of Europe's largest archaeological parks, Selinunte, with its Greek temples and fascinating ruins located on the Sicilian coast.",
                "duration" => 1,
                'show' => 1
            ],
            [
                "name_it" => "Grotte Mangiapane e Riserva Naturale Orientata Monte Cofano",
                "name_en" => "Mangiapane caves and Monte Cofano Nature Reserve",
                "price_increment" => 5,
                "price" => 170,
                "abstract_it" => "Esplora le grotte Mangiapane e la Riserva Naturale di Monte Cofano.",
                "abstract_en" => "Explore the Mangiapane caves and Monte Cofano Nature Reserve.",
                "description_it" => "Scopri le grotte Mangiapane, abitate fin dal Paleolitico, e la Riserva Naturale di Monte Cofano con le sue spettacolari formazioni rocciose e la fauna selvatica.",
                "description_en" => "Discover the Mangiapane caves, inhabited since the Paleolithic era, and the Monte Cofano Nature Reserve with its spectacular rock formations and wildlife.",
                "duration" => 1,
                'show' => 1
            ],
            [
                "name_it" => "Marsala città, Saline dello stagnone Ettore Infersa e isola di Mothia",
                "name_en" => "Marsala city, Stagnone Ettore Infersa salt pans, and Mothia island",
                "price_increment" => 5,
                "price" => 220,
                "abstract_it" => "Tour della città di Marsala, delle saline e dell'isola di Mothia.",
                "abstract_en" => "Tour of Marsala city, the salt pans of Stagnone Ettore Infersa, and Mothia island.",
                "description_it" => "Esplora Marsala, famosa per il vino e le saline dello Stagnone. Visita l'isola di Mothia con i suoi resti archeologici fenici.",
                "description_en" => "Explore Marsala, famous for its wine and the salt pans of Stagnone. Visit Mothia island with its Phoenician archaeological remains.",
                "duration" => 1,
                'show' => 1
            ],
            [
                "name_it" => "Saline museo del sale Trapani",
                "name_en" => "Salt museum Trapani salt pans",
                "price_increment" => 5,
                "price" => 160,
                "abstract_it" => "Visita il museo del sale di Trapani.",
                "abstract_en" => "Visit the salt museum at Trapani salt pans.",
                "description_it" => "Scopri la tradizione della raccolta del sale a Trapani visitando il museo del sale, situato nelle storiche saline della città.",
                "description_en" => "Discover the tradition of salt collection in Trapani by visiting the salt museum, located in the city's historic salt pans.",
                "duration" => 1,
                'show' => 1
            ]
        ];

        foreach ($excursions as $excursion) {
            Excursion::create($excursion);
        }
    }
}
