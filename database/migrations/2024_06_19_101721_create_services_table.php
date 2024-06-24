<?php

use App\Models\Service;
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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('subtitle');
            $table->string('subtitleSec');
            $table->string('abstract');
            $table->text('body');
            $table->string('links');
            $table->text('condition');
            $table->timestamps();
        });

        $services = [
            [
                "title" => "Tour delle città antiche",
                "subtitle" => "Scopri le meraviglie del passato",
                "subtitleSec" => "Visite guidate e approfondimenti storici",
                "abstract" => "Esplora antiche rovine e siti archeologici, immergendoti nella storia millenaria di civiltà perdute.",
                "body" => "Parti per un viaggio indimenticabile tra le rovine di antiche città, tra cui Pompei, Machu Picchu e Petra. Guidati da esperti locali, avrai l'opportunità di apprendere i segreti di queste civiltà e ammirare monumenti unici al mondo.",
                "links" => "https://www.touranticheroads.com",
                "condition" => "Il tour include trasporti, guide esperte e biglietti d'ingresso."
            ],
            [
                "title" => "Escursione tra le montagne",
                "subtitle" => "Avventura tra natura incontaminata",
                "subtitleSec" => "Esperienze uniche tra panorami mozzafiato",
                "abstract" => "Esplora sentieri segreti e paesaggi mozzafiato, respirando aria fresca e godendoti la natura incontaminata.",
                "body" => "Parti per un'escursione indimenticabile attraverso le Alpi svizzere. Cammina tra prati alpini, scopri laghi nascosti e goditi viste spettacolari sulle vette innevate. I nostri esperti guideranno ogni passo del viaggio, garantendo una sicurezza totale e un'esperienza indimenticabile.",
                "links" => "https://www.mountaintrek.com",
                "condition" => "Escursione di un giorno con pranzo incluso. Equipaggiamento fornito."
            ],
            [
                "title" => "Tour del vino in Toscana",
                "subtitle" => "Esplora i vigneti e degusta i migliori vini",
                "subtitleSec" => "Scopri l'arte della vinificazione",
                "abstract" => "Immergiti nella cultura del vino toscano, scoprendo le cantine più rinomate e degustando vini pregiati.",
                "body" => "Parti per un tour indimenticabile tra le colline toscane. Visita antiche cantine, scopri i segreti della vinificazione e degusta i migliori vini Chianti, Brunello di Montalcino e Super Tuscan. I nostri esperti sommelier ti guideranno attraverso un percorso di gusto e tradizione.",
                "links" => "https://www.tuscanwineexperience.com",
                "condition" => "Il tour include trasporti, degustazioni e guida specializzata."
            ],
            [
                "title" => "Escursione in barca alle isole greche",
                "subtitle" => "Naviga tra le acque cristalline dell'Egeo",
                "subtitleSec" => "Esplora isole remote e spiagge paradisiache",
                "abstract" => "Vivi un'avventura marina esplorando isole remote e acque cristalline, scoprendo la magia delle isole greche.",
                "body" => "Parti per un'escursione in barca attraverso le isole cicladiche. Naviga tra acque cristalline, scopri spiagge remote e immergiti nella cultura locale. I nostri capitani esperti ti guideranno tra gli itinerari più affascinanti, garantendo comfort e sicurezza durante tutto il viaggio.",
                "links" => "https://www.greekislandcruise.com",
                "condition" => "Crociera di 3 giorni con pensione completa e attività acquatiche incluse."
            ],
            [
                "title" => "Tour della giungla amazzonica",
                "subtitle" => "Esplorazione della biodiversità",
                "subtitleSec" => "Incontri con tribù indigene e fauna selvatica",
                "abstract" => "Avventurati tra la flora e la fauna dell'Amazzonia, vivendo esperienze uniche in uno dei polmoni verdi del pianeta.",
                "body" => "Parti per un'avventura emozionante attraverso la giungla amazzonica. Esplora sentieri nascosti, scopri piante medicinali e avvista animali selvatici unici al mondo. I nostri guide locali ti condurranno tra villaggi indigeni, garantendo un'esperienza autentica e rispettosa dell'ambiente.",
                "links" => "https://www.amazonadventure.com",
                "condition" => "Escursione di 5 giorni con alloggio in lodge e pasti inclusi."
            ],
            [
                "title" => "Transfer di lusso a Dubai",
                "subtitle" => "Viaggio esclusivo tra le torri scintillanti",
                "subtitleSec" => "Comfort e stile in una delle città più futuristiche del mondo",
                "abstract" => "Vivi un trasferimento di lusso attraverso le strade di Dubai, ammirando l'architettura moderna e lussuosa della città.",
                "body" => "Parti per un trasferimento esclusivo tra l'aeroporto e i tuoi hotel di lusso a Dubai. Viaggia a bordo di limousine di prima classe, goditi una vista mozzafiato sullo skyline urbano e vivi un'esperienza indimenticabile di comfort e stile.",
                "links" => "https://www.luxurydubaitransfers.com",
                "condition" => "Transfer privato con autista dedicato e servizi premium."
            ],
            [
                "title" => "Escursione sul Grande Muro",
                "subtitle" => "Avventura sulla muraglia millenaria",
                "subtitleSec" => "Esplorazione storica e paesaggi mozzafiato",
                "abstract" => "Esplora il simbolo della Cina antica, camminando lungo i tratti più spettacolari e storici del Grande Muro.",
                "body" => "Parti per un'avventura storica lungo il Grande Muro cinese. Percorri sezioni panoramiche, scopri torri di guardia millenarie e ammira paesaggi mozzafiato. Le nostre guide esperte ti condurranno attraverso un viaggio che mescola storia, cultura e natura, rendendo ogni passo un'esperienza indimenticabile.",
                "links" => "https://www.greatwalltrek.com",
                "condition" => "Escursione di 2 giorni con pernottamento in albergo e pasti inclusi."
            ],
            [
                "title" => "Tour delle piramidi egiziane",
                "subtitle" => "Scoperta delle meraviglie antiche",
                "subtitleSec" => "Visite esclusive alle tombe dei faraoni",
                "abstract" => "Immergiti nella storia dell'antico Egitto, scoprendo le piramidi di Giza e i segreti dei faraoni.",
                "body" => "Parti per un tour delle piramidi egiziane e dei siti archeologici circostanti. Visita il complesso di Giza, esplora le tombe nascoste e scopri la grandezza dell'antico regno. I nostri esperti guideranno ogni passo del viaggio, garantendo una profonda immersione nella storia millenaria di questa terra misteriosa.",
                "links" => "https://www.egyptianpyramidtours.com",
                "condition" => "Tour di 4 giorni con guida archeologica e alloggio in hotel."
            ],
            [
                "title" => "Escursione tra i templi di Kyoto",
                "subtitle" => "Esplorazione dei tesori culturali",
                "subtitleSec" => "Visite a templi e giardini tradizionali",
                "abstract" => "Scopri l'eleganza e la serenità della cultura giapponese, visitando antichi templi e giardini zen a Kyoto.",
                "body" => "Parti per un'escursione culturale tra i templi di Kyoto, includendo visita ai templi di Kinkaku-ji e Ginkaku-ji, e ai giardini zen circostanti. I nostri esperti ti guideranno tra i simboli storici e spirituali di Kyoto, offrendoti una profonda comprensione della cultura giapponese e dei suoi tesori.",
                "links" => "https://www.kyototempletour.com",
                "condition" => "Escursione giornaliera con pranzo tradizionale giapponese incluso."
            ]
        ];
        
        foreach($services as $service) {
            Service::create($service);
        };

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
