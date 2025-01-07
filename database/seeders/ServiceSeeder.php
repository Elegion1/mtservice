<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                "title_it" => "Tour delle città antiche",
                "subtitle_it" => "Scopri le meraviglie del passato",
                "subtitleSec_it" => "Visite guidate e approfondimenti storici",
                "abstract_it" => "Esplora antiche rovine e siti archeologici, immergendoti nella storia millenaria di civiltà perdute.",
                "body_it" => "Parti per un viaggio indimenticabile tra le rovine di antiche città, tra cui Pompei, Machu Picchu e Petra. Guidati da esperti locali, avrai l'opportunità di apprendere i segreti di queste civiltà e ammirare monumenti unici al mondo.",
                "links" => "https://www.touranticheroads.com",
                "condition_it" => "Il tour include trasporti, guide esperte e biglietti d'ingresso.",
                'show' => 1,
                'flag' => 1
            ],
            [
                "title_it" => "Escursione tra le montagne",
                "subtitle_it" => "Avventura tra natura incontaminata",
                "subtitleSec_it" => "Esperienze uniche tra panorami mozzafiato",
                "abstract_it" => "Esplora sentieri segreti e paesaggi mozzafiato, respirando aria fresca e godendoti la natura incontaminata.",
                "body_it" => "Parti per un'escursione indimenticabile attraverso le Alpi svizzere. Cammina tra prati alpini, scopri laghi nascosti e goditi viste spettacolari sulle vette innevate. I nostri esperti guideranno ogni passo del viaggio, garantendo una sicurezza totale e un'esperienza indimenticabile.",
                "links" => "https://www.mountaintrek.com",
                "condition_it" => "Escursione di un giorno con pranzo incluso. Equipaggiamento fornito.",
                'show' => 1,
                'flag' => 1
            ],
            [
                "title_it" => "Tour del vino in Toscana",
                "subtitle_it" => "Esplora i vigneti e degusta i migliori vini",
                "subtitleSec_it" => "Scopri l'arte della vinificazione",
                "abstract_it" => "Immergiti nella cultura del vino toscano, scoprendo le cantine più rinomate e degustando vini pregiati.",
                "body_it" => "Parti per un tour indimenticabile tra le colline toscane. Visita antiche cantine, scopri i segreti della vinificazione e degusta i migliori vini Chianti, Brunello di Montalcino e Super Tuscan. I nostri esperti sommelier ti guideranno attraverso un percorso di gusto e tradizione.",
                "links" => "https://www.tuscanwineexperience.com",
                "condition_it" => "Il tour include trasporti, degustazioni e guida specializzata.",
                'show' => 1,
                'flag' => 0
            ],
            [
                "title_it" => "Escursione in barca alle isole greche",
                "subtitle_it" => "Naviga tra le acque cristalline dell'Egeo",
                "subtitleSec_it" => "Esplora isole remote e spiagge paradisiache",
                "abstract_it" => "Vivi un'avventura marina esplorando isole remote e acque cristalline, scoprendo la magia delle isole greche.",
                "body_it" => "Parti per un'escursione in barca attraverso le isole cicladiche. Naviga tra acque cristalline, scopri spiagge remote e immergiti nella cultura locale. I nostri capitani esperti ti guideranno tra gli itinerari più affascinanti, garantendo comfort e sicurezza durante tutto il viaggio.",
                "links" => "https://www.greekislandcruise.com",
                "condition_it" => "Crociera di 3 giorni con pensione completa e attività acquatiche incluse.",
                'show' => 1,
                'flag' => 0
            ],
            [
                "title_it" => "Tour della giungla amazzonica",
                "subtitle_it" => "Esplorazione della biodiversità",
                "subtitleSec_it" => "Incontri con tribù indigene e fauna selvatica",
                "abstract_it" => "Avventurati tra la flora e la fauna dell'Amazzonia, vivendo esperienze uniche in uno dei polmoni verdi del pianeta.",
                "body_it" => "Parti per un'avventura emozionante attraverso la giungla amazzonica. Esplora sentieri nascosti, scopri piante medicinali e avvista animali selvatici unici al mondo. I nostri guide locali ti condurranno tra villaggi indigeni, garantendo un'esperienza autentica e rispettosa dell'ambiente.",
                "links" => "https://www.amazonadventure.com",
                "condition_it" => "Escursione di 5 giorni con alloggio in lodge e pasti inclusi.",
                'show' => 1,
                'flag' => 1
            ],
        ];
        
        foreach($services as $service) {
            Service::create($service);
        };
    }
}
